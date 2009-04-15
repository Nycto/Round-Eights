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
     * Returns the URL the client requested
     *
     * @return \cPHP\URL
     */
    public function getURL ()
    {

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