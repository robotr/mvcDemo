<?php
/**
 * application.config.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
return array(
    'library' => array(
        // define autoloader paths to the libraries
        'SlickFW' => array(
            'locator' => 'LibLocator',
            'base_path' => './library',
            'class_dir_separator' => '\\'
        ),
        /* example for other libraries
        'Zend' => array(
            'locator' => 'LibLocator',
            'base_path' => '[path_to_]/Zend_Framework_2/library',
            'class_dir_separator' => '\\'
        )*/
    ),
    'module'   => array(
        // define autoloader paths to the modules
        'Website' => array(
            'locator' => 'WebsiteLocator',
            'base_path' => './module',
            'class_dir_separator' => '\\'
        )
    )
);
