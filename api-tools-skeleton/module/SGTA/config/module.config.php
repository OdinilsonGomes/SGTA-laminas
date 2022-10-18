<?php
return [
    'service_manager' => [
        'factories' => [
            \SGTA\V1\Rest\Turma\TurmaResource::class => \SGTA\V1\Rest\Turma\TurmaResourceFactory::class,
            \SGTA\V1\Rest\Utilizador\UtilizadorResource::class => \SGTA\V1\Rest\Utilizador\UtilizadorResourceFactory::class,
            \SGTA\V1\Rest\Aluno\AlunoResource::class => \SGTA\V1\Rest\Aluno\AlunoResourceFactory::class,
            \SGTA\V1\Rest\Transferencia\TransferenciaResource::class => \SGTA\V1\Rest\Transferencia\TransferenciaResourceFactory::class,
            \SGTA\V1\Rest\Alunov2\Alunov2Resource::class => \SGTA\V1\Rest\Alunov2\Alunov2ResourceFactory::class,
            \SGTA\V1\Rpc\Auth\JwtService::class => \SGTA\V1\Rpc\Auth\JwtServiceFactory::class,
            \SGTA\V1\Rpc\Auth\JWTChecker::class => \SGTA\V1\Rpc\Auth\JWTCheckerFactory::class,
            \Lcobucci\JWT\Configuration::class => \SGTA\V1\Rpc\Auth\JwtConfigFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'sgta.rest.turma' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/turma[/:turma_id]',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Turma\\Controller',
                    ],
                ],
            ],
            'sgta.rest.utilizador' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/utilizador[/:utilizador_id]',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Utilizador\\Controller',
                    ],
                ],
            ],
            'sgta.rest.aluno' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/aluno[/:aluno_id]',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Aluno\\Controller',
                    ],
                ],
            ],
            'sgta.rest.transferencia' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/transferencia[/:transferencia_id]',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Transferencia\\Controller',
                    ],
                ],
            ],
            'sgta.rest.turma-aluno' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/turma[/:id_turma]/aluno',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Aluno\\Controller',
                    ],
                ],
            ],
            'sgta.rpc.auth' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/auth',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rpc\\Auth\\Controller',
                        'action' => 'auth',
                    ],
                ],
            ],
            'sgta.rpc.logout' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rpc\\Logout\\Controller',
                        'action' => 'logout',
                    ],
                ],
            ],
            'sgta.rest.alunov2' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/alunov2/turma/:id_turma',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rest\\Alunov2\\Controller',
                    ],
                ],
            ],
            'sgta.rpc.refresh-token' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/refreshToken',
                    'defaults' => [
                        'controller' => 'SGTA\\V1\\Rpc\\RefreshToken\\Controller',
                        'action' => 'refreshToken',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'sgta.rest.turma',
            1 => 'sgta.rest.utilizador',
            2 => 'sgta.rest.aluno',
            3 => 'sgta.rest.transferencia',
            5 => 'sgta.rest.turmaaluno',
            6 => 'sgta.rest.turma-aluno',
            7 => 'sgta.rpc.auth',
            8 => 'sgta.rpc.logout',
            9 => 'sgta.rest.alunov2',
            10 => 'sgta.rpc.refresh-token',
        ],
        'default_version' => 1,
    ],
    'api-tools-rest' => [
        'SGTA\\V1\\Rest\\Turma\\Controller' => [
            'listener' => \SGTA\V1\Rest\Turma\TurmaResource::class,
            'route_name' => 'sgta.rest.turma',
            'route_identifier_name' => 'turma_id',
            'collection_name' => 'turma',
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
            'collection_query_whitelist' => [
                0 => 'nome_turma',
                1 => 'serie_turma',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \SGTA\V1\Rest\Turma\TurmaEntity::class,
            'collection_class' => \SGTA\V1\Rest\Turma\TurmaCollection::class,
            'service_name' => 'Turma',
        ],
        'SGTA\\V1\\Rest\\Utilizador\\Controller' => [
            'listener' => \SGTA\V1\Rest\Utilizador\UtilizadorResource::class,
            'route_name' => 'sgta.rest.utilizador',
            'route_identifier_name' => 'utilizador_id',
            'collection_name' => 'utilizador',
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
            'entity_class' => \SGTA\V1\Rest\Utilizador\UtilizadorEntity::class,
            'collection_class' => \SGTA\V1\Rest\Utilizador\UtilizadorCollection::class,
            'service_name' => 'Utilizador',
        ],
        'SGTA\\V1\\Rest\\Aluno\\Controller' => [
            'listener' => \SGTA\V1\Rest\Aluno\AlunoResource::class,
            'route_name' => 'sgta.rest.aluno',
            'route_identifier_name' => 'aluno_id',
            'collection_name' => 'aluno',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
                4 => 'POST',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [
                0 => 'nome_aluno',
            ],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \SGTA\V1\Rest\Aluno\AlunoEntity::class,
            'collection_class' => \SGTA\V1\Rest\Aluno\AlunoCollection::class,
            'service_name' => 'Aluno',
        ],
        'SGTA\\V1\\Rest\\Transferencia\\Controller' => [
            'listener' => \SGTA\V1\Rest\Transferencia\TransferenciaResource::class,
            'route_name' => 'sgta.rest.transferencia',
            'route_identifier_name' => 'transferencia_id',
            'collection_name' => 'transferencia',
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
            'entity_class' => \SGTA\V1\Rest\Transferencia\TransferenciaEntity::class,
            'collection_class' => \SGTA\V1\Rest\Transferencia\TransferenciaCollection::class,
            'service_name' => 'Transferencia',
        ],
        'SGTA\\V1\\Rest\\Alunov2\\Controller' => [
            'listener' => \SGTA\V1\Rest\Alunov2\Alunov2Resource::class,
            'route_name' => 'sgta.rest.alunov2',
            'route_identifier_name' => 'id_turma',
            'collection_name' => 'alunov2',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \SGTA\V1\Rest\Alunov2\Alunov2Entity::class,
            'collection_class' => \SGTA\V1\Rest\Alunov2\Alunov2Collection::class,
            'service_name' => 'Alunov2',
        ],
    ],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'SGTA\\V1\\Rest\\Turma\\Controller' => 'Json',
            'SGTA\\V1\\Rest\\Utilizador\\Controller' => 'Json',
            'SGTA\\V1\\Rest\\Aluno\\Controller' => 'Json',
            'SGTA\\V1\\Rest\\Transferencia\\Controller' => 'Json',
            'SGTA\\V2\\Rest\\AlunoTurma\\Controller' => 'HalJson',
            'SGTA\\V1\\Rpc\\Auth\\Controller' => 'Json',
            'SGTA\\V1\\Rpc\\Logout\\Controller' => 'Json',
            'SGTA\\V1\\Rest\\Alunov2\\Controller' => 'Json',
            'SGTA\\V1\\Rpc\\RefreshToken\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'SGTA\\V1\\Rest\\Turma\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Utilizador\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Aluno\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Transferencia\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\Auth\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\Logout\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Alunov2\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\RefreshToken\\Controller' => [
                0 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'SGTA\\V1\\Rest\\Turma\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Utilizador\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Aluno\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Transferencia\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\Auth\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\Logout\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rest\\Alunov2\\Controller' => [
                0 => 'application/json',
            ],
            'SGTA\\V1\\Rpc\\RefreshToken\\Controller' => [
                0 => 'application/json',
            ],
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \SGTA\V1\Rest\Turma\TurmaEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.turma',
                'route_identifier_name' => 'turma_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Turma\TurmaCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.turma',
                'route_identifier_name' => 'turma_id',
                'is_collection' => true,
            ],
            \SGTA\V1\Rest\Utilizador\UtilizadorEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.utilizador',
                'route_identifier_name' => 'utilizador_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Utilizador\UtilizadorCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.utilizador',
                'route_identifier_name' => 'utilizador_id',
                'is_collection' => true,
            ],
            \SGTA\V1\Rest\Aluno\AlunoCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.aluno',
                'route_identifier_name' => 'aluno_id',
                'is_collection' => true,
            ],
            \SGTA\V1\Rest\Transferencia\TransferenciaEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.transferencia',
                'route_identifier_name' => 'transferencia_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Transferencia\TransferenciaCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.transferencia',
                'route_identifier_name' => 'transferencia_id',
                'is_collection' => true,
            ],
            'SGTA\\V1\\Rest\\AlunoTurma\\AlunoTurmaEntity' => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.aluno-turma',
                'route_identifier_name' => 'aluno_turma_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Aluno\AlunoEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.aluno',
                'route_identifier_name' => 'aluno_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Alunov2\Alunov2Entity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.alunov2',
                'route_identifier_name' => 'id_turma',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \SGTA\V1\Rest\Alunov2\Alunov2Collection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'sgta.rest.alunov2',
                'route_identifier_name' => 'id_turma',
                'is_collection' => true,
            ],
        ],
    ],
    'api-tools-content-validation' => [
        'SGTA\\V1\\Rest\\Turma\\Controller' => [
            'input_filter' => 'SGTA\\V1\\Rest\\Turma\\Validator',
        ],
        'SGTA\\V1\\Rest\\Transferencia\\Controller' => [
            'input_filter' => 'SGTA\\V1\\Rest\\Transferencia\\Validator',
        ],
    ],
    'input_filter_specs' => [
        'SGTA\\V1\\Rest\\Turma\\Validator' => [],
        'SGTA\\V2\\Rest\\Turma\\Validator' => [],
        'SGTA\\V1\\Rest\\Transferencia\\Validator' => [],
    ],
    'api-tools-mvc-auth' => [
        'authorization' => [
            'SGTA\\V1\\Rest\\Turma\\Controller' => [
                'collection' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'SGTA\\V1\\Rpc\\Auth\\Controller' => \SGTA\V1\Rpc\Auth\AuthControllerFactory::class,
            'SGTA\\V1\\Rpc\\Logout\\Controller' => \SGTA\V1\Rpc\Logout\LogoutControllerFactory::class,
            'SGTA\\V1\\Rpc\\RefreshToken\\Controller' => \SGTA\V1\Rpc\RefreshToken\RefreshTokenControllerFactory::class,
        ],
    ],
    'api-tools-rpc' => [
        'SGTA\\V1\\Rpc\\Auth\\Controller' => [
            'service_name' => 'Auth',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'sgta.rpc.auth',
        ],
        'SGTA\\V1\\Rpc\\Logout\\Controller' => [
            'service_name' => 'Logout',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'sgta.rpc.logout',
        ],
        'SGTA\\V1\\Rpc\\RefreshToken\\Controller' => [
            'service_name' => 'refreshToken',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'sgta.rpc.refresh-token',
        ],
    ],
    'doctrine' => [
        'authentication' => [
            'orm_default' => [
                'object_manager' => \Doctrine\ORM\EntityManager::class,
                'identity_class' => \SGTA\V1\Rest\Utilizador\UtilizadorEntity::class,
                'identity_property' => 'email',
                'credential_property' => 'password',
            ],
        ],
    ],
];
