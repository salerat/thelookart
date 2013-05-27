<?php
namespace Friend;

return array(
    'controllers' => array(
        'invokables' => array(
            'Friend\Controller\Friend' => 'Friend\Controller\FriendController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'friends' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/friends[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Friend\Controller\Friend',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'odm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'friend' => __DIR__ . '/../view',
        ),
    ),
);