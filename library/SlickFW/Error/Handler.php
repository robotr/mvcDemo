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
        // callback for set_error_handler/set_exception_handler
        $this->_callable = function() {
            $args = func_get_args();
            if (($fh = fopen($this->_fileName, 'a'))) {
                if (5 == count($args)) {
                    // Error-Handling
                    if (isset($args[4])) {
                        if (isset($args[4]['e']) && $args[4]['e'] instanceof \Exception) {
                            // caught Exception
                            /** @var \Exception $exc */
                            $exc = $args[4]['e'];
                            unset($args[4]['e']);
                            // todo - ? $source = array_shift($args[4]); ?
                            $args[0] = Type::E_USER_EXCEPTION;
                            $args[1] = get_class($exc) . ': ' . $exc->getMessage();
                            $args[2] = $exc->getFile();
                            $args[3] = $exc->getLine();
                            $args[4] = $exc->getTraceAsString();
                            $this->_keys[]  = 'trace';
                        } else {
                            unset($args[4]);
                        }
                    }
                } elseif (1 == count($args) && $args[0] instanceof \Exception) {
                    // Exception-Handling
                    // todo sanitize method-args
                    /** @var \Exception $exc */
                    $exc = array_shift($args);
                    $args[0] = Type::E_USER_CATCHABLE;
                    $args[1] = 'Uncaught ' . get_class($exc) . ': ' . $exc->getMessage();
                    $args[2] = $exc->getFile();
                    $args[3] = $exc->getLine();
                    $args[4] = $exc->getTraceAsString();
                    $this->_keys[]  = 'trace';
                }
                // create log-message
                $args = array_combine($this->_keys, $args);
                $args['code'] = Type::getName($args['code']);
                $args['date'] = date('r');
                fwrite($fh, (!in_array('trace', $this->_keys)) ?
                    $this->_getMessage($args) : $this->_getTraceMessage($args));
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
            set_exception_handler($this->_callable);
        } else {
            // reset to default
            set_error_handler(NULL);
            set_exception_handler(NULL);
        }
        return $this;
    }

    /**
     * return message for common errors
     * @param array $params
     * @return string
     */
    private function _getMessage($params)
    {
        return sprintf('[%s] [%s] %s in %s on line %s' . PHP_EOL,
            $params['date'], $params['code'], $params['msg'],
            $params['file'], $params['line']);
    }

    /**
     * return message with additional backtrace
     * @param array $params
     * @return string
     */
    private function _getTraceMessage($params)
    {
        return sprintf('[%s] [%s] %s in %s on line %s' . PHP_EOL .
            'Stack-Trace:' . PHP_EOL . '  %s' . PHP_EOL,
            $params['date'], $params['code'], $params['msg'], $params['file'], $params['line'],
            str_replace(PHP_EOL, PHP_EOL . '  ', $params['trace']));
    }

}
