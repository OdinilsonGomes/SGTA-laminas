<?php
return [
    'controllers' => [
        'factories' => [
            'status\\V1\\Rpc\\Ping\\Controller' => \status\V1\Rpc\Ping\PingControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'status.rpc.ping' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ping',
                    'defaults' => [
                        'controller' => 'status\\V1\\Rpc\\Ping\\Controller',
                        'action' => 'ping',
                    ],
                ],
            ],
            'status.rest.ping-rest' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/ping-rest[/:ping_rest_id]',
                    'defaults' => [
                        'controller' => 'status\\V1\\Rest\\PingRest\\Controller',
                    ],
                ],
            ],
            'status.rest.teste' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/teste[/:teste_id]',
                    'defaults' => [
                        'controller' => 'status\\V1\\Rest\\Teste\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'status.rpc.ping',
            1 => 'status.rest.ping-rest',
            2 => 'status.rest.teste',
        ],
    ],
    'api-tools-rpc' => [
        'status\\V1\\Rpc\\Ping\\Controller' => [
            'service_name' => 'ping',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'status.rpc.ping',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'status\\V1\\Rpc\\Ping\\Controller' => 'Json',
            'status\\V1\\Rest\\PingRest\\Controller' => 'HalJson',
            'status\\V1\\Rest\\Teste\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'status\\V1\\Rpc\\Ping\\Controller' => [
                0 => 'application/json',
            ],
            'status\\V1\\Rest\\PingRest\\Controller' => [
                0 => 'application/json',
            ],
            'status\\V1\\Rest\\Teste\\Controller' => [
                0 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'status\\V1\\Rpc\\Ping\\Controller' => [
                0 => 'application/json',
            ],
            'status\\V1\\Rest\\PingRest\\Controller' => [
                0 => 'application/json',
            ],
            'status\\V1\\Rest\\Teste\\Controller' => [
                0 => 'application/json',
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'status\\V1\\Rpc\\Ping\\Controller' => [
            'input_filter' => 'status\\V1\\Rpc\\Ping\\Validator',
        ],
        'status\\V1\\Rest\\PingRest\\Controller' => [
            'input_filter' => 'status\\V1\\Rest\\PingRest\\Validator',
        ],
        'status\\V1\\Rest\\Teste\\Controller' => [
            'input_filter' => 'status\\V1\\Rest\\Teste\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'status\\V1\\Rpc\\Ping\\Validator' => [
            0 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'ack',
                'description' => 'acknowledge the api',
                'field_type' => 'ack',
                'error_message' => 'hjngn',
            ],
            1 => [
                'required' => true,
                'validators' => [],
                'filters' => [],
                'name' => 'ack1',
                'description' => 'ack1 testing then system',
                'field_type' => 'ack1',
            ],
        ],
        'status\\V1\\Rest\\PingRest\\Validator' => [],
        'status\\V1\\Rest\\Teste\\Validator' => [
            0 => [
                'name' => 'descr',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '150',
                        ],
                    ],
                ],
            ],
            1 => [
                'name' => 'data',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            2 => [
                'name' => 'detail',
                'required' => true,
                'filters' => [
                    0 => [
                        'name' => \Laminas\Filter\StringTrim::class,
                    ],
                    1 => [
                        'name' => \Laminas\Filter\StripTags::class,
                    ],
                ],
                'validators' => [
                    0 => [
                        'name' => \Laminas\Validator\StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => '65535',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \status\V1\Rest\PingRest\PingRestResource::class => \status\V1\Rest\PingRest\PingRestResourceFactory::class,
        ],
    ],
    'api-tools-rest' => [
        'status\\V1\\Rest\\PingRest\\Controller' => [
            'listener' => \status\V1\Rest\PingRest\PingRestResource::class,
            'route_name' => 'status.rest.ping-rest',
            'route_identifier_name' => 'ping_rest_id',
            'collection_name' => 'ping_rest',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ],
            'collection_http_methods' => [],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \status\V1\Rest\PingRest\PingRestEntity::class,
            'collection_class' => \status\V1\Rest\PingRest\PingRestCollection::class,
            'service_name' => 'pingRest',
        ],
        'status\\V1\\Rest\\Teste\\Controller' => [
            'listener' => 'status\\V1\\Rest\\Teste\\TesteResource',
            'route_name' => 'status.rest.teste',
            'route_identifier_name' => 'teste_id',
            'collection_name' => 'teste',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \status\V1\Rest\Teste\TesteEntity::class,
            'collection_class' => \status\V1\Rest\Teste\TesteCollection::class,
            'service_name' => 'teste',
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \status\V1\Rest\PingRest\PingRestEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'status.rest.ping-rest',
                'route_identifier_name' => 'ping_rest_id',
                'hydrator' => \Doctrine\Laminas\Hydrator\DoctrineObject::class,
            ],
            \status\V1\Rest\PingRest\PingRestCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'status.rest.ping-rest',
                'route_identifier_name' => 'ping_rest_id',
                'is_collection' => true,
            ],
            \status\V1\Rest\Teste\TesteEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'status.rest.teste',
                'route_identifier_name' => 'teste_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \status\V1\Rest\Teste\TesteCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'status.rest.teste',
                'route_identifier_name' => 'teste_id',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools' => [
        'db-connected' => [
            'status\\V1\\Rest\\Teste\\TesteResource' => [
                'adapter_name' => 'database_adapter',
                'table_name' => 'teste',
                'hydrator_name' => \Laminas\Hydrator\ArraySerializableHydrator::class,
                'controller_service_name' => 'status\\V1\\Rest\\Teste\\Controller',
                'entity_identifier_name' => 'id',
                'table_service' => 'status\\V1\\Rest\\Teste\\TesteResource\\Table',
            ],
        ],
    ],
];
