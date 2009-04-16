<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Env
 */

namespace cPHP\Env;

/**
 * Collects information about the current request and allows readonly access to it
 */
class Request implements \cPHP\iface\Env\Request
{

    /**
     * The server variables given to PHP
     *
     * @var array
     */
    private $server;

    /**
     * The variables posted by the client
     *
     * @var array
     */
    private $post;

    /**
     * The files uploaded by the client
     *
     * @var array
     */
    private $files;

    /**
     * The parsed query string
     *
     * @var array
     */
    private $get;

    /**
     * The URL the client requested
     *
     * @var \cPHP\URL
     */
    private $url;

    /**
     * Helper function that returns whether a given array has key with a
     * non-empty value
     *
     * @param Array $array The array to test
     * @param String $key The key to test
     * @return Boolean
     */
    static public function hasKey( array &$array, $key )
    {
        if ( !array_key_exists($key, $array) )
            return FALSE;

        if ( \cPHP\isEmpty($array[$key]) )
            return FALSE;

        return TRUE;
    }

    /**
     * Constructor...
     *
     * @param array $server The $_SERVER array
     * @param array $post The $_POST array
     * @param array $files The $_FILES array
     */
    public function __construct ( array $server, array $post, array $files )
    {
        $this->server = $server;
        $this->post = $post;
        $this->files = $files;
    }

    /**
     * Returns the data posted by the client
     *
     * @return array
     */
    public function getPost ()
    {
        return $this->post;
    }

    /**
     * Returns the query string parsed as an array
     *
     * @return array
     */
    public function getGet ()
    {
        // Lazy instantiation
        if ( !isset($this->get) ) {
            $parser = new \cPHP\QueryParser;
            $this->get = $parser->parse( $this->server['QUERY_STRING'] );
        }

        return $this->get;
    }

    /**
     * Returns the list of files uploaded by the client
     *
     * @return array
     */
    public function getFiles ()
    {
        return $this->files;
    }

    /**
     * Private method for building the URL object
     *
     * @return \cPHP\URL
     */
    private function buildURL ()
    {
        $url = new \cPHP\URL;

        // Get the url Scheme from the server protocol
        if ( self::hasKey($this->server, "SERVER_PROTOCOL") )
            $url->setScheme( strtolower( strstr( $this->server['SERVER_PROTOCOL'], "/", TRUE ) ) );

        // Pull the server host, if it is set
        if ( self::hasKey($this->server, 'HTTP_HOST') )
            $url->setHost($this->server['HTTP_HOST']);

        // If there is no host, pull the IP address
        else if ( self::hasKey($this->server, "SERVER_ADDR") )
            $url->setHost($this->server['SERVER_ADDR']);

        // Pull the port
        if ( self::hasKey($this->server, "SERVER_PORT") )
            $url->setPort( intval( $this->server['SERVER_PORT'] ) );

        // The path and file name
        if ( self::hasKey($this->server, 'SCRIPT_NAME') )
            $url->setPath( $this->server['SCRIPT_NAME'] );

        // The faux directories
        if ( self::hasKey( $this->server, 'PATH_INFO' ) )
            $url->setFauxDir( \cPHP\str\head( $this->server['PATH_INFO'], "/" ) );

        // Finally, pull the the URL query
        if ( self::hasKey($this->server, 'QUERY_STRING') )
            $url->setQuery($this->server['QUERY_STRING']);

        return $url;
    }

    /**
     * Returns the URL the client requested
     *
     * @return \cPHP\URL This will return a clone of the url object every
     *      time it is called
     */
    public function getURL ()
    {
        if ( !isset($this->url) )
            $this->url = $this->buildURL();

        return clone $this->url;
    }

    /**
     * Returns the file requested by the client, in the context of the file system
     *
     * @return \cPHP\FileSys\File
     */
    public function getFile ()
    {

    }

}

?>