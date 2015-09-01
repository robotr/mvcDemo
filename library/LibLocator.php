<?php
/**
 * LibLocator.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * LibLocator
 * the library ServiceLocator class
 */
class LibLocator implements SlickFW\Autoloader\Locator\ILocator
{
    /**
     * @var string
     */
    protected $_base;

    /**
     * @var string
     */
    private $_classSeparator;

    public function __construct($baseDir, $separator)
    {
        $this->_base = (string)$baseDir;
        $this->_classSeparator = (string)$separator;
    }

    /**
     * {@inheritdoc}
     */
    public function canLocate($class)
    {
        $path = $this->getPath($class);
        if (file_exists($path)) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath($class)
    {
        return $this->_base . DIRECTORY_SEPARATOR .
            str_replace($this->_classSeparator, DIRECTORY_SEPARATOR, $class) . '.php';
    }
}