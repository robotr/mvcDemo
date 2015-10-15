<?php
/**
 * World.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * World
 * @package Website\Model\Db
 */

namespace Website\Model\Db;

use Website\Setup;

class World
{
    /**
     * @throws \Exception
     * @return array
     */
    public function getCountries()
    {
        $result = [];
        /** @var \SlickFW\Service\Db $dbService */
        $dbService = Setup::getInstance('Website')->get(['Database' => 'default']);
        try {
            $db = $dbService->getAdapter();
        } catch (\Exception $e) {
            throw $e;
        }
        $sth = null;
        if (isset($db)) {
        //$dbService->select('Country', array('Name', 'Continent', 'Region'))->assemble();
            $sth = $db->prepare('SELECT * FROM Country');
            /** @var $sth \PDOStatement */
            if ($sth instanceof \PDOStatement && $sth->execute()) {
                $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        return $result;
    }
}