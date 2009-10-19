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
 * @package Soap
 */

namespace h2o\Soap\Server;

/**
 * Collects and processes the header element of a soap message
 */
class Header
{

    /**
     * The list of registered header processors
     *
     * @var array
     */
    private $headers = array();

    /**
     * The list of roles this server is acting under
     *
     * @var Array An array of URIs
     */
    private $roles = array();

    /**
     * Constructor...
     *
     * @param String $ultimateURI The URI of the "UltimateReceiver" Soap Role
     * @param String $nextURI The URI of the "Next" Soap role
     */
    public function __construct (
        $ultimateURI = "http://www.w3.org/2003/05/soap-envelope/role/ultimateReceiver",
        $nextURI = "http://www.w3.org/2003/05/soap-envelope/role/next"
    ) {
        $this->addRole( $ultimateURI );
        $this->addRole( $nextURI );
    }

    /**
     * Returns the Roles this server acts under
     *
     * @return Array Returns an array of URIs as String
     */
    public function getRoles ()
    {
        return $this->roles;
    }

    /**
     * Adds a new Role this server acts under
     *
     * @param String $role
     * @return \h2o\Soap\Server\Header Returns a self reference
     */
    public function addRole ( $role )
    {
        $role = \h2o\Filter::URL()->filter($role);

        if ( !\h2o\isEmpty($role) && !in_array($role, $this->roles) )
            $this->roles[] = $role;

        return $this;
    }

    /**
     * Returns the Headers registered for processing
     *
     * @return Array Returns an array of \h2o\iface\Soap\Header objects
     */
    public function getHeaders ()
    {
        return $this->headers;
    }

    /**
     * Registers a new header processor
     *
     * @param String $uri The URI of the header
     * @param String $name The tag name of the header this object will handle
     * @param \h2o\iface\Soap\Header $operation The handler to invoke when
     * 		this command is encountered
     * @return \h2o\Soap\Server Returns a self reference
     */
    public function addHeader ( $uri, $name, \h2o\iface\Soap\Header $header )
    {
        $uri = (string) trim( $uri );
        $name = \h2o\str\stripW( $name );

        if ( \h2o\isEmpty($uri) )
            throw new \h2o\Exception\Argument(0, "Header URI", "Must not be empty");

        if ( \h2o\isEmpty($name) )
            throw new \h2o\Exception\Argument(1, "Header Tag Name", "Must not be empty");

        if ( !isset($this->headers[ $uri ]) )
            $this->headers[ $uri ] = array();

        $this->headers[ $uri ][ $name ] = $header;

        return $this;
    }

    /**
     * Processes a soap request through this server
     *
     * @param \h2o\Soap\Parser $parser The soap message to process
     * @return \h2o\iface\XMLBuilder Returns the builder needed to construct
     * 		the response
     */
    public function process ( \h2o\Soap\Parser $parser )
    {

    }

}

?>