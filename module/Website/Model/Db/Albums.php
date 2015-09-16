<?php
/**
 * Albums.php
 */
/**
 * Albums
 * @package Website\Model\Db
 */
namespace Website\Model\Db;

use Website\Setup;

class Albums
{
    /**
     * @throws \Exception
     * @return array
     */
    public function getAlbums()
    {
        $result = array();
        /** @var \SlickFW\Service\Db $dbService */
        $dbService = Setup::getInstance('Website')->get(array('Database' => 'other'));
        try {
            $db = $dbService->getAdapter();
        } catch (\Exception $e) {
            throw $e;
        }
        $sth = null;
        if (isset($db)) {
            $sth = $db->prepare('SELECT * FROM album');
            /** @var $sth \PDOStatement */
            if ($sth instanceof \PDOStatement && $sth->execute()) {
                $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
            }
        }
        return $result;
    }
}