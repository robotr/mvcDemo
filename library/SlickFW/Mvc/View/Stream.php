<?php
/**
 * Stream.php
 * @copyright Copyright © 2015 cloud-nemo
 * @author    cloud-nemo
 */
/**
 * SlickFW\Mvc\View\Stream
 * class for use as custom streamWrapper
 * @link http://php.net/manual/en/class.streamwrapper.php
 * @package SlickFW\Mvc\View
 */

namespace SlickFW\Mvc\View;

class Stream
{
    /**
     * current stream position
     * @var int
     */
    protected $_pos = 0;

    /**
     * stream data
     * @var string
     */
    protected $_data;

    /**
     * Stream stats
     * @var array
     */
    protected $_stat;

    /**
     * Opens the script file and converts markup
     * @param string $path
     * @param string $mode
     * @param array $options
     * @param int $openedPath
     * @return bool
     */
    public function stream_open($path, $mode, $options, &$openedPath)
    {
        // get the view script source
        $path        = str_replace('slick.view://', '', $path);
        $this->_data = file_get_contents($path);

        /**
         * If reading the file failed, update our local stat store
         * to reflect the real stat of the file, then return on failure
         */
        if ($this->_data === false) {
            $this->_stat = stat($path);
            return false;
        }

        /**
         * replace short-open tags, parse php-tags
         */
        $this->_data = preg_replace('/\<\?\=/', '<?php echo ', $this->_data);
        $this->_data = preg_replace('/<\?(?!xml|php)/s', '<?php ', $this->_data);

        /**
         * force update PHP's stat cache, to prevent additional reads
         * should the script be requested again
         */
        $this->_stat = stat($path);

        return true;
    }

    /**
     * added to make __FILE__ return the appropriate info on stat()-calls
     * @return array
     */
    public function url_stat()
    {
        return $this->_stat;
    }

    /**
     * reads data from the
     * @param int $count
     * @return string
     */
    public function stream_read($count)
    {
        $ret = substr($this->_data, $this->_pos, $count);
        $this->_pos += strlen($ret);
        return $ret;
    }

    /**
     * get the current position in the stream-read pointer
     * @return int
     */
    public function stream_tell()
    {
        return $this->_pos;
    }

    /**
     * returns if the end of the stream is reached
     * @return bool
     */
    public function stream_eof()
    {
        return $this->_pos >= strlen($this->_data);
    }

    /**
     * get stream (script-file) statistics
     * @return array
     */
    public function stream_stat()
    {
        return $this->_stat;
    }

    /**
     * lookup stream-data from/at a certain position in the stream
     * @param int $offset
     * @param int $whence
     * @return bool
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->_data) && $offset >= 0) {
                    $this->_pos = $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_CUR:
                if ($offset >= 0) {
                    $this->_pos += $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            case SEEK_END:
                if (strlen($this->_data) + $offset >= 0) {
                    $this->_pos = strlen($this->_data) + $offset;
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }
}