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
        Listener::route(__NAMESPACE__);
        $logger = $this->get(array('Logger' => 'file'));
        if ($logger instanceof \SlickFW\Error\Logger) {
            $logger->log('Your Module "' . __NAMESPACE__ . '" is set-up!',
                __FILE__, __LINE__, E_NOTICE);
        }
    }
}
