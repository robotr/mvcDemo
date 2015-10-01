<?php
/**
 * ModelAbstract.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Service\Db\ModelAbstract
 * @package SlickFW\Service\Db\Model
 */

namespace SlickFW\Service\Db;

abstract class ModelAbstract
{
    /**
     * @var array
     */
    protected $_config = [];

    /**
     * @var \PDO|null
     */
    protected $_conn = null;

    /**
     * @var string
     */
    protected $_dbName = null;

    /**
     * @var string
     */
    protected $_driverName = null;

    /**
     * the connection status
     * @var mixed|null
     */
    protected $_state = false;

    /**
     * array of supported PDO drivers
     * @var array
     */
    protected $_supported = [];

    /**
     * ctor abstract database-class to load the concrete driver-class with
     * @param array $conf
     * @param string $dbName
     * @throws \Exception
     * @return ModelAbstract
     */
    public function __construct($conf, $dbName = '')
    {
        if (!empty($conf)) {
            $this->_config = $conf;
        } else {
            throw new \Exception('Database-Error: No configuration found!', 1005);
        }
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->_dbName;
    }

    /**
     * return PDO-drivers name
     * @return string
     */
    public function getDriver()
    {
        return $this->_driverName;
    }

    /**
     * retrieve the database-connection
     * @throws \Exception
     * @return \PDO
     */
    protected function _getConnection()
    {
        if (isset($this->_config['dbname'])) {
            $this->_dbName = $this->_config['dbname'];
        } else {
            $this->_dbName = null;
        }
        if (!empty($this->_config)
            && isset($this->_config['dbtype']) && isset($this->_config['host'])
            && isset($this->_config['user']) && isset($this->_config['pass'])
            && isset($this->_config['options'])
        ) {
            $dbName = null;
            if (!empty($this->_dbName)) {
                $dbName = ';dbname=' . $this->_dbName;
            }
            $port = null;
            if (!empty($this->_config['port'])) {
                $port = ';' . $this->_config['port'];
            }
            $dsn = $this->_config['dbtype'] . ':host=' . $this->_config['host'] . $port . $dbName;
            try {
                $this->_conn =
                    new \PDO($dsn, $this->_config['user'], $this->_config['pass'], $this->_config['options']);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        if ($this->_conn instanceof \PDO) {
            $this->_driverName = $this->_conn->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $this->_supported = $this->_conn->getAvailableDrivers();
            $this->_state = $this->_conn->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        }
        return $this->_conn;
    }

    /**
     * get config-parameters
     * @return array
     */
    protected function _getConfig()
    {
        return $this->_config;
    }

}
