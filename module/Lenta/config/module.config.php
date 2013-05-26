<?php
namespace Lenta;

return array(
    'controllers' => array(
        'invokables' => array(
            'Lenta\Controller\Lenta' => 'Lenta\Controller\LentaController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'lenta' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/lenta[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-z0-9]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Lenta\Controller\Lenta',
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
            'lenta' => __DIR__ . '/../view',
        ),
    ),
);