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
 * EventSystem Trait
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
trait EventSystem
{
    // {{{ properties
    // }}} properties
    // {{{ methods
    // {{{ run
    /**
     * Runs the particular event, and ads 'before', 'on' and 'after' hooks
     *
     * @param string $event
     *            Event name
     * @param mixed
     *            Rest of the parameters that gonna be passed to actions and plugins
     *
     * @access protected
     * @return void
     */
    protected function run($event)
    {
        $args = func_get_args();
        
        $event = array_shift($args);
        call_user_func_array(array(
            $this,
            'dispatch'
        ), array_merge(array(
            'before' . $event
        ), $args));
        call_user_func_array(array(
            $this,
            'dispatch'
        ), array_merge(array(
            'on' . $event
        ), $args));
        call_user_func_array(array(
            $this,
            'dispatch'
        ), array_merge(array(
            'after' . $event
        ), $args));
    }
    // }}} run
    // {{{ dispatch
    /**
     * Executes actions and registered plugins for particular hook
     *
     * @param string $hook
     *            Hook name
     * @param mixed
     *            Rest of the parameters that gonna be passed to action and plugins
     *
     * @access protected
     * @return void
     */
    protected function dispatch($hook)
    {
        var_dump($hook);
        $args = func_get_args();
        $hook = array_shift($args);
        
        if (method_exists($this, $hook)) {
            call_user_func_array(array(
                $this,
                $hook
            ), $args);
        }
        if (isset($this->plugins)) {
            foreach ($this->plugins as $plugin) {
                if (method_exists($plugin, $hook)) {
                    call_user_func_array(array(
                        $plugin,
                        $hook
                    ), array_merge(array(
                        $this
                    ), $args));
                }
            }
        }
    }
    // }}} dispatch
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
