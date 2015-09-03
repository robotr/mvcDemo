<?php
/**
 * Setup.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Website\Setup
 * class to manage module-specific setup
 *
 * @package   Website
 * @category  module
 */

namespace Website;

use SlickFW\Mvc\ModuleSetup;
use SlickFW\Router\Listener;

class Setup extends ModuleSetup
{
    /**
     * @return void
     */
    public function run()
    {
        //*
        $logger = $this->get(array('Logger' => 'file'));// */
        Listener::route(__NAMESPACE__);
    }
}
