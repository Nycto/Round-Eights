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
 * @package XMLBuilder
 */

namespace cPHP\XMLBuilder;

/**
 * Wraps another XML builder in a soap envelope
 */
class Soap implements \cPHP\iface\XMLBuilder
{

    /**
     * The builder to use for generating the soap body
     *
     * @var \cPHP\iface\XMLBuilder
     */
    private $body;

    /**
     * The builder to use for generating the soap header
     *
     * @var \cPHP\iface\XMLBuilder
     */
    private $header;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\XMLBuilder $body The builder to use for generating the soap body
     * @param \cPHP\iface\XMLBuilder $header The builder to use for generating the soap header
     */
    public function __construct ( \cPHP\iface\XMLBuilder $body, \cPHP\iface\XMLBuilder $header = null )
    {
        $this->body = $body;
        $this->header = $header;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        // Put together the root soap envelope node with all the needed namespaces
        $soapEnv = $doc->createElementNS("http://www.w3.org/2003/05/soap-envelope", "soap:Envelope");
        $soapEnv->setAttribute("xmlns:xsd", "http://www.w3.org/2001/XMLSchema");
        $soapEnv->setAttribute("xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");

        // Add the soap header, if it has been defined
        if ( $this->header ) {
            $soapHeader = $doc->createElement("soap:Header");
            $soapEnv->appendChild( $soapHeader );

            $soapHeader->appendChild(
                    \cPHP\XMLBuilder::buildNode( $this->header, $doc )
                );
        }

        // Add the soap body node
        $soapBody = $doc->createElement("soap:Body");
        $soapEnv->appendChild( $soapBody );

        $soapBody->appendChild(
                \cPHP\XMLBuilder::buildNode( $this->body, $doc )
            );

        return $soapEnv;
    }

}

?>