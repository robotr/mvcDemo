<?php
/**
 * Db.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Service\Db
 * @package SlickFW\Service
 */

namespace SlickFW\Service;

use SlickFW\Service\Db\ModelAbstract as DbAbstract;

class Db extends DbAbstract
{
    /**
     * check for connection status and return a new PDO instance in case of success
     * @throws \Exception
     * @return \PDO
     */
    public function getAdapter()
    {
        if (!$this->_conn instanceof \PDO || !$this->_state) {
            try {
                return $this->_getConnection();
            }  catch (\Exception $e) {
                throw $e;
            }
        }
        return $this->_conn;
    }

}
