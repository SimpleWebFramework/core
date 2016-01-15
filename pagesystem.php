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
 * PageSystem Trait
 *
 * @author Piotr Siudak <piotr.siudak@gmail.com>
 */
trait PageSystem
{
    // {{{ properties
    /**
     * Location where page definitions are, every page in its own directory
     *
     * @access private
     * @var string
     */
    private $pagesLocation;
    /**
     * Store used to pass data from form preparation to form display and from action to response
     *
     * @access private
     * @var array
     */
    private $transit;
    
    // }}} properties
    // {{{ methods
    // {{{ setupPages
    /**
     * Sets up the location where pages are located
     *
     * @access protected
     *
     * @param string $path Path to directory holding page definitions
     *
     * @return void
     */
    protected function setupPages($path)
    {
        $this->pagesLocation = realpath($path);
    }
    // }}} setupPages
    // {{{ pageExists
    /**
     * Checks whether a file of specified type exists in page directory
     *
     * @param string $type Type of file (validator|formprep|form|action|view)
     *
     * @access private
     * @return bool TRUE if the file of specified type exists; FALSE otherwise.
     */
    private function pageExists($type)
    {
        return file_exists($this->getPageFileLocation($type));
    }
    // }}} pageExists
    // {{{ getPageFileLocation
    /**
     * Returns path to file of specified type in page directory
     *
     * @param string $type Type of file (validator|formprep|form|action|view)
     *
     * @access public
     * @return string file path
     */
    public function getPageFileLocation($type)
    {
        $request = Request::getInstance();
        
        return join(DIRECTORY_SEPARATOR, array(
            $this->pagesLocation,
            $request->resource,
            $type . '.php'
        ));
    }
    // }}} getPageFileLocation
    // {{{ runPages
    /**
     * This is application's router.
     *
     * It's logic is as follows:
     *
     * validator - decides if we need to show form (or do we ned to show form again, but with an error message)
     * if there is a error in a form (eg some required fields empty) or there is no data in $_POST
     *
     * formprep - prepares data for display in form (eg reads data from db)
     * form - displays form
     *
     * if there is no error (or there is no validator whatsoever) app will skip to
     *
     * action - app performs action( eg saves to db )
     * response - app outputs data to client
     *
     * @access protected
     * @return void
     */
    protected function runPages()
    {
        try {
            if (! $this->pageExists('action') && ! $this->pageExists('response')) {
                throw new CoreAppError('Not Found', 404);
            }
            try {
                if ($this->pageExists('validator')) {
                    $this->run('validator');
                }
                if ($this->pageExists('action')) {
                    $this->run('action');
                }
                $request = Request::getInstance();
                
                if ($this->pageExists('response')) {
                    $this->run('response');
                }
            } catch (FormException $e) {
                if (! $this->pageExists('form')) {
                    
                    throw new CoreAppError('Not Found', 404);
                }
                if ($this->pageExists('formprep')) {
                    $this->run('formprep');
                }
                $this->run('form');
            }
        } catch (\Exception $e) {
            $this->run('error', $e);
        }
    }
    // }}} runPages
    // {{{ onValidator
    /**
     * Proceedes to include validator file for a page
     *
     * @access private
     * @return void
     */
    private function onValidator()
    {
        $request = Request::getInstance();
        include $this->getPageFileLocation('validator');
    }
    // }}} onValidator
    // {{{ onFormPrep
    /**
     * Proceedes to include form preparation file for a page and send data to form
     *
     * @access private
     * @return void
     */
    private function onFormPrep()
    {
        $request = Request::getInstance();
        include $this->getPageFileLocation('formprep');
        $this->transit = get_defined_vars();
    }
    // }}} onFormPrep
    // {{{ onForm
    /**
     * Receives data from form preparation and proceedes to include form file for a page
     *
     * @access private
     * @return void
     */
    private function onForm()
    {
        foreach ($this->transit as $varName => $varValue) {
            $$varName = $varValue;
        }
        include $this->getPageFileLocation('form');
    }
    // }}} onForm
    // {{{ onAction
    /**
     * Proceedes to include action file for a page and sends generated data to response
     *
     * @access private
     * @return void
     */
    private function onAction()
    {
        $request = Request::getInstance();
        include $this->getPageFileLocation('action');
        $this->transit = get_defined_vars();
    }
    // }}} onAction
    // {{{ onResponse
    /**
     * Receives data from action and proceedes to include response file for a page
     *
     * @access private
     * @return void
     */
    private function onResponse()
    {
        foreach ($this->transit as $varName => $varValue) {
            $$varName = $varValue;
        }
        include $this->getPageFileLocation('response');
    }
    // }}} onResponse
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
