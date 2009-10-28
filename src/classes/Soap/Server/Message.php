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
 * Collects and processes the message elements of a soap request
 */
class Message
{

    /**
     * The list of registered message processors
     *
     * @var array
     */
    private $messages = array();

    /**
     * Returns the Messages registered for processing
     *
     * @return Array Returns an array of \h2o\iface\Soap\Message objects
     */
    public function getMessages ()
    {
        return $this->messages;
    }

    /**
     * Registers a new message processor
     *
     * @param String $uri The URI of the message
     * @param String $name The tag name of the message this object will handle
     * @param \h2o\iface\Soap\Message $operation The handler to invoke when
     * 		this command is encountered
     * @return \h2o\Soap\Server Returns a self reference
     */
    public function addMessage ( $uri, $name, \h2o\iface\Soap\Message $message )
    {
        $uri = (string) trim( $uri );
        $name = \h2o\str\stripW( $name );

        if ( \h2o\isEmpty($uri) )
            throw new \h2o\Exception\Argument(0, "Message URI", "Must not be empty");

        if ( \h2o\isEmpty($name) )
            throw new \h2o\Exception\Argument(1, "Message Tag Name", "Must not be empty");

        if ( !isset($this->messages[ $uri ]) )
            $this->messages[ $uri ] = array();

        $this->messages[ $uri ][ $name ] = $message;

        return $this;
    }

    /**
     * Processes a soap request through this server
     *
     * @param \h2o\Soap\Parser $parser The soap message to process
     * @return \h2o\XMLBuilder\Series Returns the builder needed to construct
     * 		the response messages
     */
    public function process ( \h2o\Soap\Parser $parser )
    {
        $messages = $parser->getMessages();

        $response = new \h2o\XMLBuilder\Series;

        foreach ( $messages AS $message )
        {
            if ( !isset( $this->messages[ $message->getNamespace() ][ $message->getTag() ] ) )
                continue;

            $result =
                $this->messages[ $message->getNamespace() ][ $message->getTag() ]
                ->process( $message );

            if ( $result instanceof \h2o\iface\XMLBuilder )
                $response->addChild( $result );
        }

        return $response;
    }

}

?>