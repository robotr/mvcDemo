<?php
/**
 * ModuleInterface.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * ModuleInterface
 * @package SlickFW\Mvc
 */

namespace SlickFW\Mvc;

interface ModuleInterface
{
    /**
     * final setup-step of the the current module-instance before route-dispatching begins
     * @return void
     */
    public function run();
}