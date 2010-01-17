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

namespace r8\Soap;

/**
 * Parses out the various parts of a soap request
 */
class Parser
{

    /**
     * The document to parse
     *
     * @var \DOMDocument
     */
    private $doc;

    /**
     * A shared xpath registered against the document being parsed
     *
     * @var \DOMXPath
     */
    private $xpath;

    /**
     * The Namespace to use for the soap nodes
     *
     * @var String
     */
    private $namespace;

    /**
     * Constructor...
     *
     * @param \DOMDocument $doc The document to parse
     * @param String $namespace The namespace URI for the soap nodes
     */
    public function __construct ( \DOMDocument $doc, $namespace = "http://www.w3.org/2003/05/soap-envelope" )
    {
        $this->doc = $doc;
        $this->namespace = (string) $namespace;

        $this->xpath = new \DOMXPath( $doc );
        $this->xpath->registerNamespace("soap", $this->namespace );
    }

    /**
     * Counts the number of message nodes in this document
     *
     * @return Integer
     */
    public function countMessages ()
    {
        return (int) $this->xpath->evaluate("count(/soap:Envelope/soap:Body/*)");
    }

    /**
     * Performs standard checks on the document
     *
     * @throws \r8\Soap\Fault Thrown if any problems are encountered
     * @return null
     */
    public function ensureBasics ()
    {
        if ( !$this->doc->hasChildNodes() ) {
            throw new \r8\Soap\Fault(
                "Document is Empty",
                "Sender",
                array("Parser", "EmptyDoc")
            );
        }

        // Look for the soap envelope
        if ( $this->xpath->evaluate("count(/soap:Envelope)") == 0 ) {
            throw new \r8\Soap\Fault(
                "Could not find a SOAP Envelope node",
                "Sender",
                array("Parser", "MissingEnvelope")
            );
        }

        // I couldn't resist this variable name.
        $bodyCount = $this->xpath->evaluate("count(/soap:Envelope/soap:Body)");

        // Look for the soap body
        if ( $bodyCount == 0 ) {
            throw new \r8\Soap\Fault(
                "Could not find a SOAP Body node",
                "Sender",
                array("Parser", "MissingBody")
            );
        }

        // Look for the soap body
        if ( $bodyCount > 1 ) {
            throw new \r8\Soap\Fault(
                "Multiple SOAP Body nodes found",
                "Sender",
                array("Parser", "MultiBody")
            );
        }

        // Ensure there is at least one message
        if ( $this->countMessages() == 0 ) {
            throw new \r8\Soap\Fault(
                "No Message Nodes found",
                "Sender",
                array("Parser", "NoMessage")
            );
        }

        // Count the number of header nodes
        $headerCount = $this->xpath->evaluate("count(/soap:Envelope/soap:Header)");

        if ( $headerCount > 1 ) {
            throw new \r8\Soap\Fault(
                "Multiple SOAP Header nodes found",
                "Sender",
                array("Parser", "MultiHeader")
            );
        }
    }

    /**
     * Returns the list of Soap Header nodes
     *
     * @return \Iterator Returns an Iterator filled with \r8\Soap\Node\Header objects
     */
    public function getHeaders ()
    {
        $this->ensureBasics();

        $ns = $this->namespace;

        return new \r8\Iterator\Filter(
            new \r8\Iterator\DOMNodeList(
                $this->xpath->query("/soap:Envelope/soap:Header/*")
            ),
            new \r8\Curry\Call(function ( $node ) use ($ns) {
                return new \r8\Soap\Node\Header( $node, $ns );
            })
        );
    }

    /**
     * Returns the list of Soap Header nodes
     *
     * @return \Iterator Returns an Iterator filled with \r8\Soap\Node\Message objects
     */
    public function getMessages ()
    {
        $this->ensureBasics();

        $ns = $this->namespace;

        return new \r8\Iterator\Filter(
            new \r8\Iterator\DOMNodeList(
                $this->xpath->query("/soap:Envelope/soap:Body/*")
            ),
            new \r8\Curry\Call(function ( $node ) use ($ns) {
                return new \r8\Soap\Node\Message( $node, $ns );
            })
        );
    }

}

?>