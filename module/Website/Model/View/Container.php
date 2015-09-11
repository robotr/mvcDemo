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

use SlickFW\Error\Type;
use SlickFW\Mvc\Model\ViewContainer;
use Website\Model\Db\World;
use Website\Setup;

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
        } catch (\Exception $e) {
            $log = Setup::getInstance('Website')->get(array('Logger' => 'file'));
            $log->error($e, Type::E_USER_EXCEPTION);
        } finally {
            if (isset($tableData)) {
                $this->add(array('_testData' => var_export($tableData, true)));
            } else {
                $this->add(array('_testData' => var_export(array('error' => 'Database-Error'), true)));
            }
        }
    }

}
