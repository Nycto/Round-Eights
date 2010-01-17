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
 * @package Soap
 */

namespace r8\Soap\Server;

/**
 * Collects and processes the message elements of a soap request
 */
class Messages
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
     * @return Array Returns an array of \r8\iface\Soap\Message objects
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
     * @param \r8\iface\Soap\Message $operation The handler to invoke when
     *      this command is encountered
     * @return \r8\Soap\Server\Messages Returns a self reference
     */
    public function addMessage ( $uri, $name, \r8\iface\Soap\Message $message )
    {
        $uri = (string) trim( $uri );
        $name = \r8\str\stripW( $name );

        if ( \r8\isEmpty($uri) )
            throw new \r8\Exception\Argument(0, "Message URI", "Must not be empty");

        if ( \r8\isEmpty($name) )
            throw new \r8\Exception\Argument(1, "Message Tag Name", "Must not be empty");

        if ( !isset($this->messages[ $uri ]) )
            $this->messages[ $uri ] = array();

        $this->messages[ $uri ][ $name ] = $message;

        return $this;
    }

    /**
     * Processes a soap request through this server
     *
     * @param \r8\Soap\Parser $parser The soap message to process
     * @return \r8\XMLBuilder\Series Returns the builder needed to construct
     *      the response messages
     */
    public function process ( \r8\Soap\Parser $parser )
    {
        $messages = $parser->getMessages();

        $response = new \r8\XMLBuilder\Series;

        foreach ( $messages AS $message )
        {
            if ( !isset( $this->messages[ $message->getNamespace() ][ $message->getTag() ] ) )
                continue;

            $result =
                $this->messages[ $message->getNamespace() ][ $message->getTag() ]
                ->process( $message );

            if ( $result instanceof \r8\iface\XMLBuilder )
                $response->addChild( $result );
        }

        return $response;
    }

}

?>