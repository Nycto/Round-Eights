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
 * @package FileFinder
 */

namespace cPHP\Cache;

/**
 * Adapter for the Memcache extension to the standardized cache interface
 */
class Memcache implements \cPHP\iface\Cache
{

    /**
     * The host of the Memcache server
     *
     * @var String
     */
    private $host;

    /**
     * The port to connect to
     *
     * @var Integer
     */
    private $port;

    /**
     * Whether to open a persistent connection
     *
     * @var Boolean
     */
    private $persistent;

    /**
     * The connection timeout value in seconds
     *
     * @var Integer
     */
    private $timeout;

    /**
     * The actual connection to the server
     *
     * @var \Memcache
     */
    private $link;

    /**
     * Constructor...
     *
     * @param String $host The host of the Memcache server
     * @param Integer $port The port to connect to
     * @param Boolean $persistent Whether to connect with a persistent connection
     * @param Integer $timeout The connection timeout value in seconds
     */
    public function __construct ( $host = "127.0.0.1", $port = 11211, $persistent = FALSE, $timeout = 1 )
    {
        $this->host = trim( \cPHP\strval( $host ) );
        if ( \cPHP\isEmpty($this->host) )
            throw new \cPHP\Exception\Argument(0, "Memcache Host", "Must not be empty");

        $this->port = intval( $port );
        if ( $this->port < 0 )
            throw new \cPHP\Exception\Argument(1, "Memcache Port", "Must be at least 0");

        $this->persistent = \cPHP\boolval( $persistent );

        $this->timeout = intval( $timeout );
        if ( $this->timeout < 0 )
            throw new \cPHP\Exception\Argument(1, "Memcache Port", "Must be greater than 0");
    }

}

?>