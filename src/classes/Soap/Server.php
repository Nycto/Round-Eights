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
 * @package Soap
 */

namespace r8\Soap;

/**
 * Delegates a Soap request to the appropriate registered message processors
 */
class Server
{

    /**
     * The processor for handling soap messages
     *
     * @var \r8\Soap\Server\Messages
     */
    private $messages;

    /**
     * The processor for handling soap headers
     *
     * @var \r8\Soap\Server\Headers
     */
    private $headers;

    /**
     * The namespace to use for soap elements
     *
     * @var String
     */
    private $namespace = "http://www.w3.org/2003/05/soap-envelope";

    /**
     * Constructor...
     *
     * @param \r8\Soap\Server\Messages $message The message processor
     * @param \r8\Soap\Server\Messages $message The header processor
     */
    public function __construct (
        \r8\Soap\Server\Messages $message = null,
        \r8\Soap\Server\Headers $header = null
    ) {
        $this->messages = empty($message) ? new \r8\Soap\Server\Messages : $message;
        $this->headers = empty($header) ? new \r8\Soap\Server\Headers : $header;
    }

    /**
     * Returns the list of registered messages
     *
     * @return \r8\Soap\Server\Messages
     */
    public function getMessages ()
    {
        return $this->messages;
    }

    /**
     * Returns the Headers registered for processing
     *
     * @return \r8\Soap\Server\Headers
     */
    public function getHeaders ()
    {
        return $this->headers;
    }

    /**
     * Adds a new Role this server acts under
     *
     * @param String $role
     * @return \r8\Soap\Server Returns a self reference
     */
    public function addRole ( $role )
    {
        $this->headers->addRole( $role );
        return $this;
    }

    /**
     * Registers a new header processor
     *
     * @param String $uri The URI of the header
     * @param String $name The tag name of the header this object will handle
     * @param \r8\iface\Soap\Header $operation The handler to invoke when
     * 		this command is encountered
     * @return \r8\Soap\Server Returns a self reference
     */
    public function addHeader ( $uri, $name, \r8\iface\Soap\Header $header )
    {
        $this->headers->addHeader( $uri, $name, $header );
        return $this;
    }

    /**
     * Registers a new message processor
     *
     * @param String $uri The URI of the message
     * @param String $name The tag name of the message this object will handle
     * @param \r8\iface\Soap\Message $operation The handler to invoke when
     * 		this command is encountered
     * @return \r8\Soap\Server Returns a self reference
     */
    public function addMessage ( $uri, $name, \r8\iface\Soap\Message $message )
    {
        $this->messages->addMessage( $uri, $name, $message );
        return $this;
    }

    /**
     * Processes a soap request through this server
     *
     * @param \r8\Soap\Parser $parser The soap message to process
     * @return \r8\iface\XMLBuilder Returns the builder needed to construct
     * 		the response
     */
    public function process ( \r8\Soap\Parser $parser )
    {
        try {
            $headers = $this->headers->process( $parser );
            $body = $this->messages->process( $parser );
        }
        catch ( \r8\Soap\Fault $err ) {
            $headers = null;
            $body = new \r8\XMLBuilder\Soap\Fault( $err, $this->namespace );
        }

        return new \r8\XMLBuilder\Soap\Envelope( $body, $headers, $this->namespace );
    }

    /**
     * A helper method for processing a stream
     *
     * @param \r8\iface\Stream\In $stream The source data
     * @return \r8\iface\XMLBuilder Returns the builder needed to construct
     * 		the response
     */
    public function processStream ( \r8\iface\Stream\In $stream )
    {
        $doc = new \DOMDocument;
        $doc->loadXML( $stream->readAll() );
        return $this->process( new \r8\Soap\Parser($doc) );
    }

}

?>