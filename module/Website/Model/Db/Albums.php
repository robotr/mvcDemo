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
        $result = [];
        /** @var \SlickFW\Service\Db $dbService */
        $dbService = Setup::getInstance('Website')->get(['Database' => 'other']);
        try {
            $db = $dbService->getAdapter();
        } catch (\Exception $e) {
            throw $e;
        }
        $sth = null;
        if (isset($db)) {
            $sth = $db->prepare($dbService->select('album'));
            /** @var $sth \PDOStatement */
            if ($sth instanceof \PDOStatement) {
                if ($sth->execute()) {
                    $result = $sth->fetchAll(\PDO::FETCH_OBJ);
                } else {
                    $err = $sth->errorInfo();
                }
            } else {
                $err = $db->errorInfo();
            }
            if (isset($err) && NULL !== $err[1] && NULL !== $err[2]) {
                // log any database-error that might have occured
                Setup::getInstance('Website')->get(['Logger' => 'file'])
                    ->error('[Code: ' . $err[1] . '] ' . $err[2]);
            }
        }
        return $result;
    }
}
