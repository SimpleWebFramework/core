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
 * BasicErrorHandling Class
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
class BasicErrorHandling extends Plugin
{
    // {{{ properties
    // }}} properties
    // {{{ methods
    // {{{ onError
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
    public function onError(CoreApp $app, CoreAppError $e)
    {
        $request = Request::getInstance();
        $request->referrer = $request->resource;
        $request->resource = 'error';
        
        if (file_exists($app->getPageFileLocation('action')) && file_exists($app->getPageFileLocation('response'))) {
            include $app->getPageFileLocation('action');
            include $app->getPageFileLocation('response');
        } else {
            $section_id = '';
            $error_code = (int) $e->getCode();
            $error_message = $e->getMessage();
            
            switch ($error_code) {
                case 403:
                    header('HTTP/1.1 403 Forbidden');
                    $error_message = ' 403 Forbidden';
                    break;
                case 404:
                    header('HTTP/1.1 404 Not Found');
                    $error_message = '404 Not Found';
                    break;
                default:
                    header('HTTP/1.1 500 Internal Server Error');
                    $error_message = '500 Internal Server Error ';
                    $error_code = 500;
                    break;
            }
            
            echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">' . "\n";
            echo '<html>' . "\n";
            echo '<head>' . "\n";
            echo '<meta content="text/html; charset=UTF-8" ';
            echo 'http-equiv="content-type">' . "\n";
            echo '<title>' . $error_message . '</title>' . "\n";
            echo '</head>' . "\n";
            echo '<body>' . "\n";
            echo '<h1>' . $error_message . '</h1>' . "\n";
            echo '</body>' . "\n";
            echo '</html>' . "\n";
        }
    }
    // }}} onError
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
