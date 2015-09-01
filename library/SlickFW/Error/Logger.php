<?php
/**
 * Logger.php
 */
/**
 * Logger
 * @category  SlickFW\Error
 * @package SlickFW
 */

namespace SlickFW\Error;

class Logger
{
    /**
     * @var string
     */
    protected $_defaultLogFile = 'http_php_error.log';

    /**
     * @var string
     */
    protected $_fileName = '';

    /**
     * creates a logger to as an application-service
     * @param array $config - array of configuration-parameters
     * <br><b>requires at least a configuration-entry named "log_dir" with a read/write-accessible path relative to the
     * directory of the invoking file</b>
     * @throws \Exception
     */
    public function __construct($config = array())
    {
        if (isset($config['log_dir'])) {
            if (is_dir($path = realpath($config['log_dir'])) || is_dir($path = $config['log_dir'])) {
                $this->_fileName = $path . '/' . $this->_defaultLogFile;
            } else {
                throw new \Exception('Log-Directory "' . $path . '" not found or inaccessible!');
            }
        } else {
            // @todo implement other logger types
            throw new \Exception('No "log_dir" specified in configuration!');
        }
    }

    /**
     * log an error of the given type
     * @param string $message
     * @param string $file
     * @param int $line
     * @param int $type
     * @throws \Exception
     */
    public function log($message, $file, $line, $type = E_ERROR)
    {
        if (!empty($this->_fileName) && ($fh = fopen($this->_fileName, 'a'))) {
            fwrite($fh, sprintf('[' . date('r') . '] [' . Type::getName($type) .
                '] %s in %s on line %s' . PHP_EOL, $message, $file, $line));
            fclose($fh);
        } else {
            throw new \Exception('No Log-File specified or file not accessible!');
        }
    }

}