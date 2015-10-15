<?php
/**
 * Query.php
 */
/**
 * Query
 * @package SlickFW\Service\Db\Table
 */

namespace SlickFW\Service\Db\Table;

class Query
{
    /** select-all wildcard character */
    const WILDCARD = '*';

    /**
     * @var Query
     */
    protected static $_query;

    public $queryString;

    /**
     * private ctor - hence the singleton-pattern
     */
    private function __construct()
    {
        $this->queryString = '';
    }

    /**
     * return a db-query instance
     * @return Query
     */
    public static function getInstance()
    {
        if (!isset(self::$_query)) {
            self::$_query = new self;
        }
        return self::$_query;
    }

    /**
     * builds a select query-string
     * @return string
     */
    public function assemble()
    {
        return $this->queryString;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->queryString;
    }
}