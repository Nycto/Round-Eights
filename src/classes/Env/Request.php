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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package Env
 */

namespace r8\Env;

/**
 * Collects information about the current request and allows readonly access to it
 */
class Request implements \r8\iface\Env\Request
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
     * @var \r8\iface\Input
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
     * The request headers
     *
     * @var array
     */
    private $headers;

    /**
     * Whether the script is running in command line mode
     *
     * @var boolean
     */
    private $cli;

    /**
     * The URL the client requested
     *
     * @var \r8\URL
     */
    private $url;

    /**
     * The requested file, relative to the file system
     *
     * @var \r8\FileSys\File
     */
    private $file;

    /**
     * Helper function that returns whether a given array has key with a
     * non-empty value
     *
     * @param Array $array The array to test
     * @param String $key The key to test
     * @return Boolean
     */
    static private function hasKey ( array &$array, $key )
    {
        if ( !array_key_exists($key, $array) )
            return FALSE;

        if ( \r8\isEmpty($array[$key]) )
            return FALSE;

        return TRUE;
    }

    /**
     * Constructor...
     *
     * @param array $server The $_SERVER array
     * @param \r8\iface\Input $post The POST input data
     * @param \r8\Input\Files $files The uploaded files
     * @param array $headers The list of request headers
     * @param Boolean $cli Whether the script was invoked via the command line
     */
    public function __construct (
        array $server = array(),
        \r8\iface\Input $post = null,
        \r8\Input\Files $files = null,
        array $headers = array(),
        $cli = FALSE
    ) {
        $this->server = $server;
        $this->post = $post ? $post : new \r8\Input\Void;
        $this->files = $files ? $files : new \r8\Input\Files;
        $this->headers = $headers;
        $this->cli = (bool) $cli;
    }

    /**
     * Returns the data posted by the client
     *
     * @return \r8\iface\Input
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
            $parser = new \r8\QueryParser;
            $data = $parser->parse( $this->server['QUERY_STRING'] );
            $this->get = new \r8\Input\Reference( $data );
        }

        return $this->get;
    }

    /**
     * Returns the list of files uploaded by the client
     *
     * @return \r8\Input\files
     */
    public function getFiles ()
    {
        return $this->files;
    }

    /**
     * Private method for building the URL object
     *
     * @return \r8\URL
     */
    private function buildURL ()
    {
        $url = new \r8\URL;

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
            $url->setPort( (int) $this->server['SERVER_PORT'] );

        // The path and file name
        if ( self::hasKey($this->server, 'SCRIPT_NAME') )
            $url->setPath( $this->server['SCRIPT_NAME'] );

        // The faux directories
        if ( self::hasKey( $this->server, 'PATH_INFO' ) )
            $url->setFauxDir( \r8\str\head( $this->server['PATH_INFO'], "/" ) );

        // Finally, pull the the URL query
        if ( self::hasKey($this->server, 'QUERY_STRING') )
            $url->setQuery($this->server['QUERY_STRING']);

        return $url;
    }

    /**
     * Returns the URL the client requested
     *
     * @return \r8\URL This will return a clone of the url object every
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
     * @return \r8\FileSys\File
     */
    public function getFile ()
    {
        if ( !isset($this->file) ) {
            $this->file = new \r8\FileSys\File;

            if ( self::hasKey($this->server, 'SCRIPT_FILENAME') )
                $this->file->setPath( $this->server['SCRIPT_FILENAME'] );
        }

        return clone $this->file;
    }

    /**
     * Returns whether this request was made via command line
     *
     * @return Boolean
     */
    public function isCLI ()
    {
        return $this->cli;
    }

    /**
     * Returns whether this request was made over a secure connection
     *
     * @return Boolean
     */
    public function isSecure ()
    {
        if ( self::hasKey($this->server, 'HTTPS') ) {

            if ( $this->server['HTTPS'] == 1 )
               return TRUE;

            else if ( $this->server['HTTPS'] == 'on' )
               return TRUE;

        }

        if ( self::hasKey($this->server, 'SERVER_PORT') && $this->server['SERVER_PORT'] == 443 )
            return TRUE;

        return FALSE;
    }

    /**
     * Returns the request headers
     *
     * @return array
     */
    public function getHeaders ()
    {
        return $this->headers;
    }

}

?>