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

// {{{ trait
/**
 * PluginSystem trait
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
trait PluginSystem
{
    // {{{ properties
    /**
     * Store for plugins
     *
     * @access private
     * @var \SplObjectStorage
     */
    private $plugins;
    // }}} properties
    // {{{ methods
    // {{{ setupPluginStore
    /**
     * initiates SplObjectStorage as a plugin store
     *
     * @access protected
     * @return void
     */
    protected function setupPluginStore()
    {
        $this->plugins = new \SplObjectStorage();
    }
    // }}} setupPluginStore
    // {{{ attach
    /**
     * Adds Plugin to plugin store
     *
     * @param Plugin $plugin
     *            Plugin to add
     *            
     * @access private
     * @return void
     */
    private function attach(Plugin $plugin)
    {
        $this->plugins->attach($plugin);
    }
    // }}} attach
    // {{{ detach
    /**
     * Removes Plugin from plugin store
     *
     * @param
     *            Plugin Plugin to remove
     *            
     * @access private
     * @return void
     */
    private function detach(Plugin $plugin)
    {
        $this->plugins->detach($plugin);
    }
    // }}} detach
    // {{{ loadPlugin
    /**
     * Loads plugin and adds it to the store
     *
     * @param
     *            string name of the plugin to add
     *            
     * @access public
     * @return void
     */
    public function loadPlugin($className)
    {
        $this->attach(new $className($this));
    }
    // }}} loadPlugin
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
