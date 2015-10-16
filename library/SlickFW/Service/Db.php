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
use SlickFW\Service\Db\Table\Query;

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

    /**
     * generate a table-select statement
     * @param string $from
     * @param array|string $what
     * @return Query
     */
    public function select($from, $what = Query::WILDCARD)
    {
        $query = Query::getInstance();
        if (!empty($from)) {
            $query->queryString = 'SELECT ';
            if (!is_array($what) && is_string($what)) {
                 $query->queryString .= ((Query::WILDCARD !== $what) ? '`' . $what . '`' : $what);
                 $query->queryString .= ' FROM `' . $from . '`';
            } else {
                if (empty($what)) {
                    return $this->select($from);
                }
                foreach ($what as $num => $col) {
                    $query->queryString .= '`' . $col;
                    $query->queryString .= ($num + 1 < count($what)) ? '`, ' : '`';
                }
                $query->queryString .= ' FROM `' . $from . '`';
            }
        }
        return $query;
    }

}
