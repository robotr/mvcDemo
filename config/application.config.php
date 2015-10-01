<?php
/**
 * application.config.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
return [
    'library' => [
        // define autoloader paths to the libraries
        'SlickFW' => [
            'locator' => 'LibLocator',
            'base_path' => './library',
            'class_dir_separator' => '\\'
        ],/*
        // example for other libraries
        'Zend' => [
            'locator' => 'LibLocator',
            'base_path' => '[path_to_]/Zend_Framework_2/library',
            'class_dir_separator' => '\\'
        ]*/
    ],
    'module'   => [
        // define autoloader paths to the modules
        'Website' => [
            'locator' => 'WebsiteLocator',
            'base_path' => './module',
            'class_dir_separator' => '\\'
        ]
    ]
];
