<?php
/**
 * Container.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * Container
 * @package Website\Model\View
 */

namespace Website\Model\View;

use SlickFW\Mvc\Model\ViewContainer;
use Website\Model\Db\World;

class Container extends ViewContainer
{

    public function getCountries()
    {
        $db = new World(/*$config[$which]*/);
        $tableData = $db->getCountries();
//        $this->add(array('_' . $which => print_r($tableData)));
        $this->add(array('_testData' => var_export($tableData, true)));
    }

}
