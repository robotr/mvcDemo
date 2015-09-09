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
    /**
     * fetch countries from DB "World" and Table "Countries"
     */
    public function getCountries()
    {
        $db = new World();
        try{
            $tableData = $db->getCountries();
            $this->add(array('_testData' => var_export($tableData, true)));
        } catch (\Exception $e) {
            throw $e;
        } finally {
            // nothing
        }
    }

}
