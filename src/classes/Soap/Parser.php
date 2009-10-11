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
 * @package XMLBuilder
 */

namespace h2o\Soap;

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
     * Constructor...
     *
     * @param \DOMDocument $doc The document to parse
     * @param String $namespace The namespace URI for the soap nodes
     */
    public function __construct ( \DOMDocument $doc, $namespace = "http://www.w3.org/2003/05/soap-envelope" )
    {
        $this->doc = $doc;

        $this->xpath = new \DOMXPath( $doc );
        $this->xpath->registerNamespace("soap", $namespace );
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
     * @throws \h2o\Soap\Fault Thrown if any problems are encountered
     * @return null
     */
    public function ensureBasics ()
    {
        if ( !$this->doc->hasChildNodes() ) {
            throw new \h2o\Soap\Fault(
            	"Document is Empty",
                "Sender",
                array("Parser", "EmptyDoc")
            );
        }

        // Look for the soap envelope
        if ( $this->xpath->evaluate("count(/soap:Envelope)") == 0 ) {
            throw new \h2o\Soap\Fault(
            	"Could not find a SOAP Envelope node",
                "Sender",
                array("Parser", "MissingEnvelope")
            );
        }

        // I couldn't resist this variable name.
        $bodyCount = $this->xpath->evaluate("count(/soap:Envelope/soap:Body)");

        // Look for the soap body
        if ( $bodyCount == 0 ) {
            throw new \h2o\Soap\Fault(
            	"Could not find a SOAP Body node",
                "Sender",
                array("Parser", "MissingBody")
            );
        }

        // Look for the soap body
        if ( $bodyCount > 1 ) {
            throw new \h2o\Soap\Fault(
            	"Multiple SOAP Body nodes found",
                "Sender",
                array("Parser", "MultiBody")
            );
        }

        // Ensure there is at least one message
        if ( $this->countMessages() == 0 ) {
            throw new \h2o\Soap\Fault(
            	"No Message Nodes found",
                "Sender",
                array("Parser", "NoMessage")
            );
        }

        // Count the number of header nodes
        $headerCount = $this->xpath->evaluate("count(/soap:Envelope/soap:Header)");

        if ( $headerCount > 1 ) {
            throw new \h2o\Soap\Fault(
            	"Multiple SOAP Header nodes found",
                "Sender",
                array("Parser", "MultiHeader")
            );
        }
    }

    /**
     * Returns the list of Soap Header nodes
     *
     * @return \h2o\Iterator\DOMNodeList Returns an Iterator with a list
     * 		of DOMNodes in it
     */
    public function getHeaders ()
    {
        $this->ensureBasics();
        return new \h2o\Iterator\DOMNodeList(
            $this->xpath->query("/soap:Envelope/soap:Header/*")
        );
    }

}

?>