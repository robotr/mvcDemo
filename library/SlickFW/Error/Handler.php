<?php
/**
 * Handler.php
 */
/**
 * Handler
 * @package SlickFW
 */

namespace SlickFW\Error;

class Handler
{
    /**
     * @var array
     */
    protected $_config;

    /**
     * @var mixed
     */
    protected $_callable;

    /**
     * @var string
     */
    protected $_logfile = 'php_error.log';

    /**
     * @var string
     */
    protected $_fileName = '';

    /**
     * @var array
     */
    protected $_keys = array('code', 'msg', 'file', 'line');

    /**
     * ctor
     * @param array $config
     */
    public function __construct($config = array())
    {
        $this->_config = $config;
        $this->_callable = function() {
            $args = func_get_args();
            if (5 == count($args) && ($fh = fopen($this->_fileName, 'a'))) {
                if (isset($args[4])) {
                    unset($args[4]);
                }
                $args = array_combine($this->_keys, $args);
                $args['code'] = Type::getName($args['code']);
                $args['date'] = date('r');
                fwrite($fh, sprintf('[%s] [%s] %s in %s on line %s' . PHP_EOL,
                    $args['date'], $args['code'], $args['msg'], $args['file'], $args['line']));
                fclose($fh);
            }
        };
    }

    /**
     * sets-the error-handler callback
     */
    public function initHandler()
    {
        if (isset($this->_config['log_level'])) {
            $errors = $this->_config['log_level'];
        } else {
            $errors = error_reporting();
        }
        if (isset($this->_config['log_dir'])
            && (is_dir($dir = realpath($this->_config['log_dir'])) || is_dir($dir = $this->_config['log_dir']))
        ) {
            $this->_fileName = $dir . '/' . $this->_logfile;
            set_error_handler($this->_callable, $errors);
        } else {
            // reset to default
            set_error_handler(NULL);
        }
    }
}
