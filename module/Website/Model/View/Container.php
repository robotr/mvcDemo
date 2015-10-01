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
use Website\Model\Db\Albums;
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
        try {
            $tableData = $db->getCountries();
        } catch (\Exception $e) {
            /** @var \SlickFW\Error\Logger $log */
            $log = Setup::getInstance('Website')->get(['Logger' => 'file']);
            $log->error($e, Type::E_USER_EXCEPTION);
        } finally {
            if (isset($tableData)) {
                $this->add(['_testData' => ['countries' => $tableData]]);
            } else {
                $this->add(['_testData' => ['countries' => ['error' => 'Database-Error']]]);
            }
        }
    }

    public function getAlbums()
    {
        $db = new Albums();
        try {
            $tableData = $db->getAlbums();
        } catch (\Exception $e) {
            /** @var \SlickFW\Error\Logger $log */
            $log = Setup::getInstance('Website')->get(['Logger' => 'file']);
            $log->error($e, Type::E_USER_EXCEPTION);
        } finally {
            if (isset($tableData)) {
                $this->add(['_testData' => ['albums' => $tableData]]);
            } else {
                $this->add(['_testData' => ['albums' => ['error' => 'Database-Error']]]);
            }
        }
    }

}
