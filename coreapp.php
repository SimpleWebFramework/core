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
 * CoreApp Class
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
class CoreApp
{
    use PluginSystem, EventSystem, PageSystem;
    // {{{ properties
    // }}} properties
    // {{{ methods
    // {{{ __construct
    /**
     * Provides basic support for error handling
     *
     * @param CoreApp $app
     *            Parent Application
     * @param CoreAppError $e
     *            Exception caught that caused the error to be processed
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->setupPluginStore();
        $this->setupPages('pages');
        $this->loadPlugin('SimpleWebFramework\Core\BasicErrorHandling');
    }
    // }}} __construct
    // {{{ go
    /**
     * Runs the app
     *
     * @access public
     * @return void
     */
    public function go()
    {
        $this->run('start');
        $this->runPages();
        $this->run('finish');
    }
    // }}} go
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
