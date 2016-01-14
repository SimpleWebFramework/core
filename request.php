<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
/*
 * This file is part of Simple Web Framework.
 *
 * (c) 2008 Piotr Siudak
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Simple Web Framework
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @link https://github.com/SimpleWebFramework/core/
 */
namespace SimpleWebFramework\Core;

// {{{ class
/**
 * Request Class
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
class Request
{
    // {{{ properties
    /**
     * Name of the page requested
     *
     * @access public
     * @var string
     */
    public $resource = '';

    /**
     * Holds page HTTP referer when redirecting
     *
     * @access public
     * @var string
     */
    public $referrer = '';

    /**
     * Stores request parameters
     *
     * @access public
     * @var array
     */
    private $params = array();
    // }}} properties
    // {{{ methods
    // }}} getInstance
    /**
     * Singleton
     *
     * @static
     *
     * @access public
     *
     * @return Request instance of request object
     */
    public static function getInstance()
    {
        static $instance;
        if (! isset($instance)) {
            $instance = new Request();
        }
        return $instance;
    }
    // }}} getInstance
    // {{{ getParameter
    /**
     * Returns value of a given parameter
     *
     * @access public
     * @param string $paramName
     *            Name of the parameter
     *
     * @return string value of a parameter
     */
    public function getParameter($paramName)
    {
        if (! empty($this->params[$paramName])) {
            return $this->params[$paramName];
        }
    }
    // }}} getParameter
    // {{{ setParameter
    /**
     * Saves value of a given parameter
     *
     * @access private
     * @param string $paramName
     *            Name of the parameter
     * @param string $paramValue
     *            Value for the parameter
     *
     */
    private function setParameter($paramName, $paramValue)
    {
        if (! empty($paramName)) {
            $this->params[$paramName] = $paramValue;
        }
    }
    // }}} setParameter
    // {{{ getURL
    /**
     * Returns the URL requested
     *
     * @access public
     * @return string URL
     *
     */
    public function getURL()
    {
        if (! empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on')) {
            $URL = 'https://';
        } else {
            $URL = 'http://';
        }
        if (! empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $URL .= $_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            $URL .= $_SERVER['HTTP_HOST'];
        }
        $URL .= $_SERVER['REQUEST_URI'];
        return $URL;
    }
    // }}} getURL
    // {{{ __construct
    /**
     * Constructor.
     * Analyzes recieved data
     *
     * @access public
     * @return string URL
     *
     */
    private function __construct()
    {
        if (! empty($_SERVER['REQUEST_URI'])) {
            $urlparams = explode('/', $_SERVER['REQUEST_URI']);
            array_shift($urlparams);
            if (count($urlparams) && 'index.php' == reset($urlparams)) {
                array_shift($urlparams);
            }
            if (count($urlparams)) {
                if (strpos(end($urlparams), '.') !== false) {
                    list ($fileName, $fileExt) = explode('.', array_pop($urlparams));
                    $this->setParameter('name', $fileName);
                    $this->setParameter('ext', $fileExt);
                }
                if (count($urlparams)) {
                    $this->resource = array_shift($urlparams);
                    if (count($urlparams)) {
                        $vn_paramNumber = count($urlparams);
                        if (count($urlparams) % 2 !== 0) {
                            $urlparams[] = '';
                        }
                        for ($i = 0; $i < count($urlparams); $i += 2) {
                            $this->setParameter($urlparams[$i], $urlparams[$i + 1]);
                        }
                    }
                }
            }
        }
        if (! strlen($this->resource)) {
            $this->resource = 'index';
        }
    }
    // }}} __construct
    // }}} methods
}
// }}} class
/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
