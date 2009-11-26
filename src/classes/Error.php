<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Error
 */

namespace r8;

/**
 * Provides a registry for Error handlers
 */
class Error
{

    /**
     * The global instance of this object
     *
     * @var \r8\Error
     */
    static private $instance;

    /**
     * The list of registered error handlers
     *
     * @var Array
     */
    private $handlers = array();

    /**
     * Returns the global instance of this object
     *
     * @return \r8\Error
     */
    static public function getInstance ()
    {
        if ( !isset(self::$instance) )
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * Returns the Handlers registered in this instance
     *
     * @return Array An array of \r8\iface\Error\Handler objects
     */
    public function getHandlers ()
    {
        return $this->handlers;
    }

    /**
     * Adds a new error handler
     *
     * @param \r8\iface\Error\Handler $handler The handler to register
     * @return \r8\Error Returns a self reference
     */
    public function register ( \r8\iface\Error\Handler $handler )
    {
        if ( !in_array($handler, $this->handlers, TRUE) )
            $this->handlers[] = $handler;

        return $this;
    }

    /**
     * Handles an error
     *
     * @param \r8\iface\Error $error The error to handle
     * @return Boolean This method will always return TRUE
     */
    public function handle ( \r8\iface\Error $error )
    {
        foreach ( $this->handlers AS $handler )
        {
            $handler->handle( $error );
        }

        return TRUE;
    }

}

?>