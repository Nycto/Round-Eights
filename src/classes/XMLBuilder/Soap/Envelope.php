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
 * @package XMLBuilder
 */

namespace r8\XMLBuilder\Soap;

/**
 * Wraps another XML builder in a soap envelope
 */
class Envelope implements \r8\iface\XMLBuilder
{

    /**
     * The builder to use for generating the soap body
     *
     * @var \r8\iface\XMLBuilder
     */
    private $body;

    /**
     * The builder to use for generating the soap header
     *
     * @var \r8\iface\XMLBuilder
     */
    private $header;

    /**
     * The namespace to use for soap nodes
     *
     * @var String
     */
    private $namespace;

    /**
     * Constructor...
     *
     * @param \r8\iface\XMLBuilder $body The builder to use for generating the soap body
     * @param \r8\iface\XMLBuilder $header The builder to use for generating the soap header
     * @param String $namespace The namespace to use for soap elements
     */
    public function __construct (
        \r8\iface\XMLBuilder $body,
        \r8\iface\XMLBuilder $header = null,
        $namespace = "http://www.w3.org/2003/05/soap-envelope"
    ) {
        $this->body = $body;
        $this->header = $header;
        $this->namespace = trim( (string) $namespace );
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
        $soapEnv = $doc->createElementNS($this->namespace, "soap:Envelope");

        // Add the soap header, if it has been defined
        if ( $this->header ) {
            $soapHeader = $doc->createElementNS($this->namespace, "Header");

            $soapEnv->appendChild( $soapHeader );

            $soapHeader->appendChild(
                    \r8\XMLBuilder::buildNode( $this->header, $doc )
                );
        }

        // Add the soap body node
        $soapBody = $doc->createElementNS($this->namespace, "Body");

        $soapEnv->appendChild( $soapBody );

        $soapBody->appendChild(
                \r8\XMLBuilder::buildNode( $this->body, $doc )
            );

        return $soapEnv;
    }

}

