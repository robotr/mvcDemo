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
     * @return array
     */
    public function getCountries()
    {
        $result = array();
        /** @var \SlickFW\Service\Db $dbService */
        $dbService = Setup::getInstance('Website')->get(array('Database' => 'default'));
        try {
            $db = $dbService->getAdapter();
        } catch (\PDOException $pE) {
            Setup::getInstance('Website')->get(array('Logger' => 'file'))
                ->log($pE->getMessage(), $pE->getFile(), $pE->getLine(), $pE->getCode());
            return $result;
        }
        $sth = null;
        if (isset($db)) {
            $sth = $db->prepare('SELECT * FROM Country');
            /** @var $sth \PDOStatement */
            if ($sth instanceof \PDOStatement && $sth->execute()) {
                $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }
}