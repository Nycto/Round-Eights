<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Env
 */

namespace h2o;

/**
 * A global registry for accessing the request and response objects
 */
class Env
{

    /**
     * The global request object
     *
     * @var \h2o\Env\iface\Request
     */
    static protected $request;

    /**
     * The global response object
     *
     * @var \h2o\Env\iface\Response
     */
    static private $response;

    /**
     * Returns the environment headers
     *
     * @return array
     */
    static private function getHeaders ()
    {
        if ( function_exists('apache_request_headers') )
            return apache_request_headers();

        return array();
    }

    /**
     * Returns PHP is operating in command line mode
     *
     * @return Boolean
     */
    static private function isCLI ()
    {
        return php_sapi_name() == "cli" ? TRUE : FALSE;
    }

    /**
     * Returns the global Env\Request instance
     *
     * @return \h2o\iface\Env\Request The singleton Env object
     */
    static public function request ()
    {
        if ( !isset(self::$request) ) {

            self::$request = new \h2o\Env\Request(
                    $_SERVER,
                    $_POST,
                    $_FILES,
                    self::getHeaders(),
                    self::isCLI()
                );
        }

        return self::$request;
    }

    /**
     * Returns the global response object
     *
     * @return \h2o\Env\iface\Response
     */
    static public function response ()
    {
        if ( !isset(self::$response) )
            self::$response = new \h2o\Env\Response;

        return self::$response;
    }

}

?>