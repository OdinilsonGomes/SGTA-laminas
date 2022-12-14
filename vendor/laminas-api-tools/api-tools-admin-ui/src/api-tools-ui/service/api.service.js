/* jshint latedef: false */
(function () {
  'use strict';

  angular
    .module('api-tools.service')
    .service('api', Api);

  Api.$inject = [ 'xhr', 'agApiPath', '$http', '$q', 'growl' ];

  function Api(xhr, agApiPath, $http, $q, growl) {

    this.http = $http;
    this.q    = $q;

    var httpMethods = [ 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    this.getApiToolsVersion = function (callback) {
      xhr.get(agApiPath + '/api-tools-version')
        .then(function (response) {
          if (typeof response.version !== 'string') {
            return callback({ version: '@dev' });
          }
          return callback(response);
        })
        .catch(function (err) {
          return callback({ version: '@dev' });
        });
    };

    this.getApiList = function(callback) {
      xhr.get(agApiPath + '/dashboard', '_embedded')
        .then(function (response) {
          response.module.forEach(function(api){
            api.selected_version = Math.max.apply(Math, api.versions);
            if (api.hasOwnProperty('_links')) {
              delete api._links;
            }
          });
          return callback(false, response.module);
        })
        .catch(function (err) {
          growl.error('Unable to fetch dashboard!', {ttl: -1});
          return callback(true, null);
        });
    };

    this.getApi = function(name, callback) {
      var moduleParam = this.normalizeModuleName(name);
      xhr.get(agApiPath + '/module/' + moduleParam)
      .then(function (response) {
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to fetch API details!', {ttl: -1});
        return callback(true, err.detail);
      });
    };

    this.newApi = function(name, callback) {
      var allowed = [ 'name' ];
      xhr.create(agApiPath + '/module', [ name ], allowed)
      .then(function (response) {
        growl.success('API created');
        return callback(false, response);
      })
      .catch(function (err) {
        switch (err.status) {
          case 500 :
            return callback(true, 'I cannot create the API module, please check if already exists');
          case 409:
            if (err.data.detail) {
              return callback(true, err.data.detail);
            }
        }
        return callback(true, 'I cannot create the API module, please enter a valid name (alpha characters)');
      });
    };

    this.deleteApi = function(name, recursive, callback) {
      var moduleParam = this.normalizeModuleName(name);
      xhr.remove(agApiPath + '/module/' + moduleParam + '?recursive=' + (recursive ? 1 : 0))
      .then(function (response) {
        growl.success('API removed');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to delete API', {ttl: -1});
        return callback(true);
      });
    };

    this.strategyExists = function (strategyName, callback) {
      xhr.get(agApiPath + '/strategy/' + strategyName)
        .then(function (response) {
          return callback(false, response.success);
        })
        .catch(function (err) {
          return callback(true, err);
        });
    };

    this.newRest = function(module, service, callback) {
      var allowed = [ 'service_name'],
          moduleParam = this.normalizeModuleName(module);
      xhr.create(agApiPath + '/module/' + moduleParam + '/rest', [ service ], allowed)
      .then(function (response) {
        growl.success('Service created');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to create service', {ttl: -1});
        switch (err.status) {
          case 409 :
            return callback(true, 'The service already exists; please choose a different name');
        }
        return callback(true, 'Unable to create service (Code: ' + err.status + '): ' + err.data.detail);
      });
    };

    this.updateGeneralRest = function(module, version, name, data, isDoctrine, callback) {
      var allowed = [
        'collection_class',
        'collection_http_methods',
        'collection_name',
        'collection_query_whitelist',
        'entity_class',
        'entity_http_methods',
        'entity_identifier_name',
        'hydrator_name',
        'page_size',
        'page_size_param',
        'resource_class',
        'route_identifier_name',
        'route_match',
        'service_name',
        'table_name',
        'adapter_name'
      ];
      if (isDoctrine) {
        allowed.push('object_manager', 'strategies', 'by_value');
      }
      var result = filterData(data, allowed);
      var path = isDoctrine ? '/doctrine/' : '/rest/';
      var moduleParam = this.normalizeModuleName(module);
      xhr.update(agApiPath + '/module/' + moduleParam + path + name, result.value, result.key)
      .then(function(response) {
        growl.success('Service updated');
        return callback(false, response);
      })
      .catch(function (err) {
        if (err.data.detail) {
          growl.error(err.data.detail, {ttl: -1});
        } else {
          growl.error('Error updating service', {ttl: -1});
        }
        return callback(true, 'Error during the update of the REST service');
      });
    };

    this.updateContentNegotiationRest = function(module, version, name, data, callback) {
      var allowed = [
        'service_name',
        'selector',
        'accept_whitelist',
        'content_type_whitelist'
      ];
      var moduleParam = this.normalizeModuleName(module);
      var result = filterData(data, allowed);
      xhr.update(agApiPath + '/module/' + moduleParam + '/rest/' + name, result.value, result.key)
      .then(function(response) {
        growl.success('Content negotiation updated');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to update content negotiation', {ttl: -1});
        return callback(true, 'Error during the update of the REST service');
      });
    };

    this.deleteRest = function(module, version, name, isDoctrine, recursive, callback) {
      var path = isDoctrine ? 'doctrine' : 'rest',
          moduleParam = this.normalizeModuleName(module);
      xhr.remove(agApiPath + '/module/' + moduleParam + '/' + path + '/' + name + '?recursive=' + (recursive ? 1 : 0))
        .then(function (response) {
          growl.success('Service deleted');
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Unable to delete service', {ttl: -1});
          return callback(true);
        });
    };

    this.getAuthorizationRest = function(module, version, name, callback) {
      var moduleParam = this.normalizeModuleName(module),
          controllerName = name.replace(/-/g, '\\');
      xhr.get(agApiPath + '/module/' + moduleParam + '/authorization')
      .then(function (response) {
        var method;
        var data = {};
        data.collection = [];
        data.entity = [];
        var collection = response[controllerName + '::__collection__'];
        for (method in collection) {
          if (collection[method]) {
            data.collection.push(method);
          }
        }
        var entity = response[controllerName + '::__entity__'];
        for (method in entity) {
          if (entity[method]) {
            data.entity.push(method);
          }
        }
        return callback(false, data);
      })
      .catch(function (err) {
        growl.error('Unable to fetch API authorization details', {ttl: -1});
        return callback(true, err.detail);
      });
    };

    this.saveAuthorizationRest = function(module, version, name, auth, callback) {
      var collection = {};
      var entity = {};
      var moduleParam = this.normalizeModuleName(module),
          controllerName = name.replace(/-/g, '\\');
      httpMethods.forEach(function(method){
        collection[method] = (auth.collection.indexOf(method) > -1);
        entity[method] = (auth.entity.indexOf(method) > -1);
      });
      var data = {};
      data[controllerName + '::__collection__'] = collection;
      data[controllerName + '::__entity__'] = entity;
      xhr.save(agApiPath + '/module/' + moduleParam + '/authorization', data)
      .then(function (response) {
        growl.success('Authorization saved');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to save authorization', {ttl: -1});
        return callback(true);
      });
    };

    this.getRestList = function(module, version, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/rest?version=' + version, '_embedded')
        .then(function (response) {
          response.rest.forEach(function(entry){
            if (entry.hasOwnProperty('_links')) {
              delete entry._links;
            }
          });
          return callback(response.rest);
        })
        .catch(function (err) {
          growl.error('Unable to fetch REST services', {ttl: -1});
          console.log('Failed to get the list of REST services', err);
          return false;
        });
    };

    this.getRpcList = function(module, version, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/rpc?version=' + version, '_embedded')
      .then(function (response) {
        response.rpc.forEach(function(entry){
          if (entry.hasOwnProperty('_links')) {
            delete entry._links;
          }
        });
        return callback(response.rpc);
      })
      .catch(function (err) {
        growl.error('Unable to fetch RPC services', {ttl: -1});
        console.log('Failed to get the list of RPC services', err);
        return false;
      });
    };

    this.getRest = function(module, version, rest, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/rest/' + rest )
      .then(function (response) {
        // Create the fields property in the response
        var rest = angular.copy(response);
        rest.fields = [];
        var i = 0;
        if (response.hasOwnProperty('_embedded')) {
          if (response._embedded.hasOwnProperty('input_filters')) {
            while (!angular.isUndefined(response._embedded.input_filters[0][i])) {
              rest.fields[i] = response._embedded.input_filters[0][i];
              i++;
            }
          }
          if (response._embedded.hasOwnProperty('documentation')) {
            delete response._embedded.documentation._links;
            rest.documentation = response._embedded.documentation;
          }
        }
        // Remove hal json properties
        delete rest._links;
        delete rest._embedded;
        return callback(rest);
      })
      .catch(function (err) {
        growl.error('Unable to fetch service', {ttl: -1});
        console.log('Failed to get the REST service', err);
        return false;
      });
    };

    this.getRestDoctrineMetadata = function(entity_manager, entity, callback) {
      xhr.get(agApiPath + '/doctrine/' + entity_manager + '/metadata/' + encodeURIComponent(entity))
        .then(function(response) {
          return callback(false, response);
        })
        .catch(function(err) {
          growl.error('Unable to fetch Doctrine metadata', {ttl: -1});
          return callback(true, 'I cannot retrieve entity metadata');
        });
    };

    this.getHydrators = function(callback) {
      xhr.get(agApiPath + '/hydrators', 'hydrators' )
      .then(function (response) {
        return callback(response);
      })
      .catch(function (err) {
        growl.error('Unable to fetch hydrators', {ttl: -1});
        console.log('Failed to get the list of Hydrators', err);
        return false;
      });
    };

    this.getContentNegotiation = function(callback) {
      xhr.get(agApiPath + '/content-negotiation', '_embedded' )
      .then(function (response) {
        response.selectors.forEach(function(entry){
          delete entry._links;
        });
        return callback(response.selectors);
      })
      .catch(function (err) {
        growl.error('Unable to fetch content-negotiation details', {ttl: -1});
        console.log('Failed to get the list of Content Negotiation', err);
        return false;
      });
    };

    this.newSelector = function(name, callback) {
      var allowed = [ 'content_name' ];
      xhr.create(agApiPath + '/content-negotiation', [ name ], allowed)
      .then(function (response) {
        growl.success('Selector created');
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to create content-negotiation selector', {ttl: -1});
        return callback(true, 'I cannot create the Selector, please enter a valid name (alpha characters)');
      });
    };

    this.deleteSelector = function(name, callback) {
      xhr.remove(agApiPath + '/content-negotiation/' + name)
      .then(function (response) {
        growl.success('Content-negotiation selector deleted');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Unable to delete content-negotiation selector', {ttl: -1});
        return callback(true);
      });
    };

    this.saveSelector = function(selector, callback) {
      var allowed = [ 'selectors' ];
      var data = angular.copy(selector);
      delete data.content_name;
      xhr.update(agApiPath + '/content-negotiation/' + selector.content_name, [ data ], allowed )
      .then(function(response) {
        growl.success('Selector updated');
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        switch (err.status) {
          case 422 :
            if (err.data.hasOwnProperty('validation_messages') &&
                err.data.validation_messages.hasOwnProperty('selectors') &&
                err.data.validation_messages.selectors.hasOwnProperty('classNotFound')) {
              growl.error('Unable to update selector due to validation errors', {ttl: -1});
              return callback(true, err.data.validation_messages.selectors.classNotFound);
            }
            break;
          }
        growl.error('Error updating selector', {ttl: -1});
        return callback(true, 'Error during the API communication');
      });
    };

    this.getValidators = function(callback) {
      xhr.get(agApiPath + '/validators', 'validators' )
      .then(function (response) {
        return callback(response);
      })
      .catch(function (err) {
        growl.error('Unable to fetch validator list', {ttl: -1});
        console.log('Failed to get the list of Validators', err);
        return false;
      });
    };

    this.getFilters = function(callback) {
      xhr.get(agApiPath + '/filters', 'filters' )
      .then(function (response) {
        return callback(response);
      })
      .catch(function (err) {
        growl.error('Unable to fetch filter list', {ttl: -1});
        console.log('Failed to get the list of Filters', err);
        return false;
      });
    };

    this.saveRestField = function(module, version, controller, fields, callback) {
      angular.forEach(fields, function (field, key) {
        if (field.hasOwnProperty('error_message') && ! field.error_message) {
          delete field.error_message;
        }
      });
      var moduleParam = this.normalizeModuleName(module);
      xhr.save(agApiPath + '/module/' + moduleParam + '/rest/' + controller + '/input-filter', fields)
      .then(function(response) {
        growl.success('Saved field');
        // Remove unused properties from the response
        delete response._links;
        delete response.input_filter_name;
        // Transform the key/values in array
        response = Object.keys(response).map(function(key) { return response[key]; });
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error saving field', {ttl: -1});
        return callback(true, 'Error during the API communication');
      });
    };

    this.getAuthorizationRpc = function(module, version, name, callback) {
      var moduleParam = this.normalizeModuleName(module),
          controllerName = name.replace(/-/g, '\\'),
          rpcName = name.replace(/-Controller$/, '').split('-').pop();
      // Actions have an initial lowercase letter
      rpcName = rpcName.charAt(0).toLowerCase() + rpcName.substr(1);
      xhr.get(agApiPath + '/module/' + moduleParam + '/authorization')
      .then(function (response) {
        var data = [];
        var controller = response[controllerName + '::' + rpcName];
        for (var method in controller) {
          if (controller[method]) {
            data.push(method);
          }
        }
        return callback(false, data);
      })
      .catch(function (err) {
        growl.error('Unable to fetch authorization details', {ttl: -1});
        return callback(true, err.detail);
      });
    };

    this.saveAuthorizationRpc = function(module, version, name, auth, callback) {
      var http = {},
          moduleParam = this.normalizeModuleName(module),
          controllerName = name.replace(/-/g, '\\'),
          rpcName = name.replace(/-Controller$/, '').split('-').pop();
      // Actions have an initial lowercase letter
      rpcName = rpcName.charAt(0).toLowerCase() + rpcName.substr(1);
      httpMethods.forEach(function(method){
        http[method] = (auth.indexOf(method) > -1);
      });
      var data = {};
      data[controllerName + '::' + rpcName] = http;
      xhr.save(agApiPath + '/module/' + moduleParam + '/authorization', data)
      .then(function (response) {
        growl.success('Authorization updated');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error saving authorization details', {ttl: -1});
        return callback(true);
      });
    };

    this.saveRpcField = function(module, version, controller, fields, callback) {
      angular.forEach(fields, function (field, key) {
        if (field.hasOwnProperty('error_message') && ! field.error_message) {
          delete field.error_message;
        }
      });
      var moduleParam = this.normalizeModuleName(module);
      xhr.save(agApiPath + '/module/' + moduleParam + '/rpc/' + controller + '/input-filter', fields)
      .then(function(response) {
        growl.success('Field saved');
        // Remove unused properties from the response
        delete response._links;
        delete response.input_filter_name;
        // Transform the key/values in array
        response = Object.keys(response).map(function(key) { return response[key]; });
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error saving field', {ttl: -1});
        return callback(true, 'Error during the API communication');
      });
    };

    this.saveRestDoc = function(module, version, restname, doc, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.save(agApiPath + '/module/' + moduleParam + '/rest/' + restname + '/doc', doc)
      .then(function(response) {
        growl.success('Documentation saved');
        // Remove unused properties from the response
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error saving documentation', {ttl: -1});
        return callback(true, 'Error during the API communication');
      });
    };

    this.saveRpcDoc = function(module, version, rpcname, doc, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.save(agApiPath + '/module/' + moduleParam + '/rpc/' + rpcname + '/doc', doc)
      .then(function(response) {
        growl.success('Documentation saved');
        // Remove unused properties from the response
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error saving documentation', {ttl: -1});
        return callback(true, 'Error during the API communication');
      });
    };

    this.newRpc = function(module, service, route, callback) {
      var allowed = [ 'service_name', 'route_match'],
          moduleParam = this.normalizeModuleName(module);
      xhr.create(agApiPath + '/module/' + moduleParam + '/rpc', [ service, route ], allowed)
      .then(function (response) {
        growl.success('Service created');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error creating service', {ttl: -1});
        switch (err.status) {
          case 409 :
            return callback(true, 'The service already exists; please choose a different name');
        }
        return callback(true, 'Error creating service (Code: ' + err.status + '): ' + err.data.detail);
      });
    };

    this.getRpc = function(module, version, rpc, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/rpc/' + rpc )
      .then(function (response) {
        // Create the fields property in the response
        var rpc = angular.copy(response);
        rpc.fields = [];
        var i = 0;
        if (response._embedded) {
          if (response._embedded.input_filters) {
            while (!angular.isUndefined(response._embedded.input_filters[0][i])) {
              rpc.fields[i] = response._embedded.input_filters[0][i];
              i++;
            }
          }
          if (response._embedded.documentation) {
            delete response._embedded.documentation._links;
            rpc.documentation = response._embedded.documentation;
          }
        }
        // Remove hal json properties
        delete rpc._links;
        delete rpc._embedded;
        return callback(false, rpc);
      })
      .catch(function (err) {
        growl.error('Unable to fetch service', {ttl: -1});
        return callback(true, 'Error getting the RPC service');
      });
    };

    this.updateGeneralRpc = function(module, version, rpc, data, callback) {
      var moduleParam = this.normalizeModuleName(module);
      var allowed = [
        'accept_whitelist',
        'content_type_whitelist',
        'controller_class',
        'http_methods',
        'route_match',
        'selector',
        'service_name'
      ];
      var result = filterData(data, allowed);
      xhr.update(agApiPath + '/module/' + moduleParam + '/rpc/' + rpc, result.value, result.key)
      .then(function(response) {
        growl.success('Service updated');
        return callback(false, response);
      })
      .catch(function (err) {
        if (err.data.detail) {
          growl.error(err.data.detail, {ttl: -1});
        } else {
          growl.error('Error updating service', {ttl: -1});
        }
        return callback(true, 'Error during the update of the RPC service');
      });
    };

    this.deleteRpc = function(module, version, name, recursive, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.remove(agApiPath + '/module/' + moduleParam + '/rpc/' + name + '?recursive=' + (recursive ? 1 : 0))
      .then(function (response) {
        growl.success('Service deleted');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error deleting service', {ttl: -1});
        return callback(true);
      });
    };

    this.getAuthentication = function (callback) {
      xhr.get(agApiPath + '/authentication')
        .then(function (response) {
          if (response.hasOwnProperty('_links')) {
            delete response._links;
          }
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Unable to fetch authentication details', {ttl: -1});
          return callback(true, null);
        });
    };

    this.addBasicAuthentication = function (auth, callback) {
      var allowed = [
        'accept_schemes',
        'htpasswd',
        'realm'
      ];
      if (!auth.hasOwnProperty('accept_schemes')) {
        auth.accept_schemes = [];
      }
      auth.accept_schemes.push('basic');
      xhr.create(agApiPath + '/authentication/http-basic', [ auth.accept_schemes, auth.htpasswd, auth.realm ], allowed)
      .then(function (response) {
        growl.success('HTTP Basic authentication adapter created');
        if (response.hasOwnProperty('_links')) {
          delete response._links;
        }
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error creating HTTP Basic authentication adapter', {ttl: -1});
        return callback(true, null);
      });
    };

    this.saveBasicAuthentication = function (auth, callback) {
      var allowed = [
        'htpasswd',
        'realm'
      ];
      xhr.update(agApiPath + '/authentication/http-basic', [ auth.htpasswd, auth.realm ], allowed)
      .then(function (response) {
        growl.success('Authentication updated');
        if (response.hasOwnProperty('_links')) {
          delete response._links;
        }
        return callback(false, response);
      })
      .catch(function (err) {
        switch (err.status) {
          case 422 :
            growl.error('Unable to update authentication due to validation errors', {ttl: -1});
            return callback(true, err.validation_messages.htpasswd[0]);
        }
        growl.error('Unable to update authentication', {ttl: -1});
        return callback(true, 'Error saving the basic authentication');
      });
    };

    this.getApiDocumentList = function(callback) {
      xhr.get(agApiPath + '/../documentation')
        .then(function (response) {
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Unable to fetch documentation', {ttl: -1});
          return callback(true, null);
        });
    };

    this.getApiDocument = function(apiName, version, callback) {
      var apiParam = this.normalizeModuleName(apiName);
      xhr.get(agApiPath + '/../documentation/' + apiParam + '-v' + version)
        .then(function (response) {
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Unable to fetch documentation', {ttl: -1});
          return callback(true, null);
        });
    };

    this.getDatabase = function(callback) {
      xhr.get(agApiPath + '/db-adapter', '_embedded')
        .then(function (response) {
          response.db_adapter.forEach(function(entry){
            delete entry._links;
          });
          return callback(true, response);
        })
        .catch(function (err) {
          growl.error('Unable to fetch database adapters', {ttl: -1});
          return callback(true, null);
        });
    };

    this.addDatabase = function(db, callback) {
      var allowed = [
        'adapter_name',
        'charset',
        'database',
        'driver',
        'hostname',
        'username',
        'password',
        'port',
        'dsn',
        'driver_options'
      ];
      var data = filterData(db, allowed);
      xhr.create(agApiPath + '/db-adapter', data.value, data.key)
      .then(function (response) {
        growl.success('Database adapter created');
        if (response.hasOwnProperty('_links')) {
          delete response._links;
        }
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error creating database adapter', {ttl: -1});
        return callback(true, null);
      });
    };

    this.saveDatabase = function(db, callback) {
      var allowed = [
        'charset',
        'database',
        'driver',
        'hostname',
        'username',
        'password',
        'port',
        'dsn',
        'driver_options'
      ];
      var data = filterData(db, allowed);
      xhr.update(agApiPath + '/db-adapter/' + db.adapter_name, data.value, data.key)
      .then(function (response) {
        growl.success('Database adapter updated');
        if (response.hasOwnProperty('_links')) {
          delete response._links;
        }
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error updating database adapter', {ttl: -1});
        return callback(true, null);
      });
    };

    this.deleteDatabase = function(name, callback) {
      xhr.remove(agApiPath + '/db-adapter/' + encodeURIComponent(name))
      .then(function (response) {
        growl.success('Database adapter removed');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error removing database adapter', {ttl: -1});
        return callback(true);
      });
    };

    this.autodiscover = function(module, version, isDoctrine, name, callback) {
      var doctrine_route = isDoctrine ? 'doctrine/' : '',
          moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/' + version + '/autodiscovery/' + doctrine_route + name)
        .then(function (response) {
          return callback(true, response);
        })
        .catch(function (err) {
          if (err.data.detail) {
            growl.error(err.data.detail, {ttl: -1});
          } else {
            if (isDoctrine) {
              growl.error('Error getting Doctrine service(s)', {ttl: -1});
            } else {
              growl.error('Error getting db service(s)', {ttl: -1});
            }
          }
          return callback(true, null);
        });
    };

    this.newDbConnected = function(module, adapter, table, callback) {
      var allowed = [ 'adapter_name', 'table_name'],
          moduleParam = this.normalizeModuleName(module);
      xhr.create(agApiPath + '/module/' + moduleParam + '/rest', [ adapter, table ], allowed)
      .then(function (response) {
        growl.success('DB-Connected service(s) created');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error creating DB-Connected service(s)', {ttl: -1});
        switch (err.status) {
          case 500 :
            return callback(true, 'I cannot create the DB-Connected service, please check if already exists');
        }
        return callback(true, 'I cannot create the DB-Connected service, please check your database server');
      });
    };

    this.getDoctrineAdapters = function (callback) {
      var httpOptions = {
        method  : 'GET',
        url     : agApiPath + '/doctrine-adapter',
        headers : { Accept: 'application/json' },
        cache   : false
      };
      var
        deferred = $q.defer(),
        promise  = $http(httpOptions),
        key = '_embedded',
        response;

      promise
        .then(function success (res) {
          if (res.status == 204) {
            return callback(true, res.status);
          }
          var data = res.data;
          deferred.resolve(data[key]);

          if (data.hasOwnProperty(key)) {
            response = data[key];
            response.doctrine_adapter.forEach(function(entry) {
              delete entry._links;
            });
            return callback(false, response);
          }
        })
        .catch(function error (err) {
          growl.error('Unable to fetch Doctrine adapters', {ttl: -1});
          deferred.reject(err);
        });

    };

    this.newDoctrine = function(module, adapter, entity, callback) {
      var allowed = [ 'object_manager', 'entity_class', 'service_name'],
          moduleParam = this.normalizeModuleName(module);
      xhr.create(agApiPath + '/module/' + moduleParam + '/doctrine/' + entity.service_name, [ adapter, entity.entity_class, entity.service_name ], allowed)
        .then(function (response) {
          growl.success('Doctrine-connected service created');
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Error creating Doctrine-connected service', {ttl: -1});
          switch (err.status) {
            case 500 :
              return callback(true, 'I cannot create the Doctrine-Connected service, please check if already exists');
          }
          return callback(true, 'I cannot create the Doctrine-Connected service, please check your database server');
        });
    };

    this.newVersion = function(module, callback) {
      var allowed = [ 'module' ];
      xhr.update(agApiPath + '/versioning', [ module ], allowed)
      .then(function (response) {
        growl.success('New API version created');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error creating new API version', {ttl: -1});
        return callback(true, null);
      });
    };

    this.setDefaultVersion = function(module, version, callback) {
      var allowed = [ 'module', 'version' ];
      xhr.update(agApiPath + '/default-version', [ module, version ], allowed)
      .then(function (response) {
        growl.success('API default version updated');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error updating default API version', {ttl: -1});
        return callback(true, null);
      });
    };

    this.getSourceCode = function(module, classname, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/source?module=' + moduleParam + '&class=' + classname)
        .then(function (response) {
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Error fetching source code', {ttl: -1});
          return callback(true, null);
        });
    };

    this.getAuthenticationAdapters = function(callback) {
      xhr.get(agApiPath + '/authentication', '_embedded', 2)
        .then(function (response) {
          response.items.forEach(function(entry){
            delete entry._links;
          });
          return callback(true, response.items);
        })
        .catch(function (err) {
          growl.error('Error fetching authentication adapters', {ttl: -1});
          return callback(true, null);
        });
    };

    this.addAuthenticationAdapter = function(auth, callback) {
      var result = getAuthenticationDataForAPI(auth);
      for(var i=0; i < result.data.length; i++) {
        if (angular.isUndefined(result.data[i])) {
          result.data.splice(i, 1);
          result.allowed.splice(i, 1);
        }
      }
      xhr.create(agApiPath + '/authentication', result.data, result.allowed, null, 2)
        .then(function (response) {
          growl.success('Authentication adapter created');
          if (response.hasOwnProperty('_links')) {
            delete response._links;
          }
          return callback(false, response);
        })
        .catch(function (err) {
          growl.error('Error creating authentication adapter', {ttl: -1});
          if (err.hasOwnProperty('data') && err.data.hasOwnProperty('detail')) {
            return callback(true, err.data.detail);
          }
          return callback(true, 'Error on authentication adapter save');
        });
    };

    this.saveAuthenticationAdapter = function(auth, callback) {
      var result = getAuthenticationDataForAPI(auth);
      var newAuth = {};
      for(var i=0; i < result.data.length; i++) {
        if (!angular.isUndefined(result.data[i])) {
          newAuth[result.allowed[i]] = result.data[i];
        }
      }
      xhr.save(agApiPath + '/authentication/' + auth.name, newAuth, 2)
      .then(function(response) {
        growl.success('Authentication adapter updated');
        // Remove unused properties from the response
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error updating authentication adapter', {ttl: -1});
        return callback(true, 'Error during the authentication adapter API save');
      });
    };

    this.saveOptionsAuthenticationAdapter = function(auth, callback) {
      xhr.save(agApiPath + '/authentication/' + auth.name, auth, 2)
      .then(function(response) {
        growl.success('Authentication adapter options updated');
        // Remove unused properties from the response
        delete response._links;
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error updating authentication adapter options', {ttl: -1});
        return callback(true, 'Error during the authentication adapter API save');
      });
    };

    this.deleteAuthenticationAdapter = function(name, callback) {
      xhr.remove(agApiPath + '/authentication/' + name, 2)
      .then(function (response) {
        growl.success('Authentication adapter removed');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error removing authentication adapter', {ttl: -1});
        return callback(true);
      });
    };

    this.getAuthenticationTypes = function (callback) {
      xhr.get(agApiPath + '/auth-type', 'auth-types', 2)
      .then(function (response) {
        var result = [];
        var value;
        var key;
        for(var i = 0; i < response.length; i++){
          if (response[i].endsWith('-basic')) {
            value = response[i].slice(0, -6);
            key   = value + ' (basic)';
          } else if (response[i].endsWith('-digest')) {
            value = response[i].slice(0, -7);
            key   = value + ' (digest)';
          } else {
            value = response[i];
            key   = value + ' (oauth2)';
          }
          result[i] = { key : key, value: value };
        }
        return callback(result);
      })
      .catch(function (err) {
        growl.error('Error fetching authentication types', {ttl: -1});
        console.log('Failed to get the list of authentication types', err);
        return false;
      });
    };

    this.getModuleAuthentication = function (module, version, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.get(agApiPath + '/module/' + moduleParam + '/authentication?version=' + version, 'authentication', 2)
      .then(function (response) {
        return callback(response);
      })
      .catch(function (err) {
        growl.error('Unable to fetch API authentication details', {ttl: -1});
        console.log('Failed to get the list of authentication types', err);
        return false;
      });
    };

    this.saveModuleAuthentication = function (module, version, auth, callback) {
      var data = { 'authentication' : auth},
          moduleParam = this.normalizeModuleName(module);
      xhr.save(agApiPath + '/module/' + moduleParam + '/authentication?version=' + version, data, 2)
      .then(function (response) {
        growl.success('API authentication adapter updated');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error updating API authentication adapter', {ttl: -1});
        return callback(true);
      });
    };

    this.deleteModuleAuthentication = function (module, version, callback) {
      var moduleParam = this.normalizeModuleName(module);
      xhr.remove(agApiPath + '/module/' + moduleParam + '/authentication?version=' + version, 2)
      .then(function (response) {
        growl.success('API authentication adapter removed');
        return callback(false, response);
      })
      .catch(function (err) {
        growl.error('Error removing API authentication adapter', {ttl: -1});
        return callback(true);
      });
    };

    /**
     * URL-encodes the API's name to avoid back-slashes from being converted to forward-slashes
     * @see https://code.google.com/p/chromium/issues/detail?id=25916
     *
     * @param {string} module
     *
     * @returns {string}
     */
    this.normalizeModuleName = function (module) {
        return encodeURIComponent(module);
    };

    function getAuthenticationDataForAPI(auth) {
      var allowed, basic, data, digest, pdo, mongo;
      switch(auth.type){
        case 'HTTP Basic':
          allowed = [ 'name', 'type', 'realm', 'htpasswd' ];
          basic = auth.basic;
          data = [ auth.name, 'basic', basic.realm, basic.htpasswd ];
          break;
        case 'HTTP Digest':
          allowed = [ 'name', 'type', 'realm', 'digest_domains', 'nonce_timeout', 'htdigest' ];
          digest = auth.digest;
          data = [ auth.name, 'digest', digest.realm, digest.digest_domains, digest.nonce_timeout, digest.htdigest ];
          break;
        case 'OAuth2 PDO':
          allowed = [ 'name', 'type', 'oauth2_type', 'oauth2_route', 'oauth2_dsn', 'oauth2_username', 'oauth2_password', 'oauth2_options' ];
          pdo = auth.pdo;
          data = [ auth.name, 'oauth2', 'pdo', pdo.oauth2_route, pdo.oauth2_dsn, pdo.oauth2_username, pdo.oauth2_password, pdo.oauth2_options ];
          break;
        case 'OAuth2 Mongo':
          allowed = [ 'name', 'type', 'oauth2_type', 'oauth2_route', 'oauth2_dsn', 'oauth2_database', 'oauth2_locator_name', 'oauth2_options' ];
          mongo = auth.mongo;
          data = [ auth.name, 'oauth2', 'mongo', mongo.oauth2_route, mongo.oauth2_dsn, mongo.oauth2_database, mongo.oauth2_locator_name, mongo.oauth2_options ];
          break;
      }
      return { data : data, allowed : allowed };
    }

    function filterData(data, allowed){
      var result = { key : [], value : [] };
      allowed.forEach(function(entry){
        if (data.hasOwnProperty(entry)) {
          result.key.push(entry);
          result.value.push(data[entry]);
        }
      });
      return result;
    }

    function capitalizeFirstLetter(string)
    {
      string = string.replace(/_(\w)/g, function(_, letter) { return letter.toUpperCase(); });
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    this.mapTagInput = function(value) {
      return value.text;
    };
  }
})();
