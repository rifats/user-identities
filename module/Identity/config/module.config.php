<?php

namespace Identity;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'identities' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/identities[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller'    => Controller\IdentityController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'identity' => __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IdentityController::class => Controller\Factory\IdentityControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\IdentityManager::class => Service\Factory\IdentityManagerFactory::class,
        ],
    ],
];

