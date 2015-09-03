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
     * @throws \PDOException
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
            /*if (null !== ($log = Setup::getInstance('Website')->get(array('Logger' => 'file')))
                && $log instanceof \SlickFW\Error\Logger
            ) {
                $log->error('Caught Exception with message "' . $pE->getMessage() . '"' . PHP_EOL
                    . 'Stack-Trace:' . PHP_EOL . '  ' . str_replace(PHP_EOL, PHP_EOL . '  ', $pE->getTraceAsString()));
            }*/
            throw $pE;
        } finally {
            $sth = null;
            if (isset($db)) {
                $sth = $db->prepare('SELECT * FROM Country');
                /** @var $sth \PDOStatement */
                if ($sth instanceof \PDOStatement && $sth->execute()) {
                    $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
                }
                return $result;
            }
        }
        return $result;
    }
}