<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package XMLBuilder
 */

namespace h2o\XMLBuilder\Soap;

/**
 * Generates a soap fault node structure
 */
class Fault implements \h2o\iface\XMLBuilder
{

    /**
     * The fault code
     *
     * @var String
     */
    private $code;

    /**
     * The human readable fault reason description
     *
     * @var String
     */
    private $reason;

    /**
     * Constructor...
     *
     * @param String $code The fault code
     * @param String $reason The human readable fault reason description
     */
    public function __construct ( $code, $reason )
    {
        $code = \h2o\strval($code);

        if ( \h2o\isEmpty($code) )
            throw new \h2o\Exception\Argument(0, "Fault Code", "Must not be empty");

        $reason = \h2o\strval($reason);

        if ( \h2o\isEmpty($reason) )
            throw new \h2o\Exception\Argument(0, "Fault Reason", "Must not be empty");

        $this->code = $code;
        $this->reason = $reason;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $fault = $doc->createElementNS(
                "http://www.w3.org/2003/05/soap-envelope",
                "soap:Fault"
            );

        $code = $doc->createElement("soap:Code");
        $code->appendChild(
                $doc->createElement("soap:Value", $this->code)
            );

        $reason = $doc->createElement("soap:Reason");
        $reason->appendChild(
                $doc->createElement("soap:Text", $this->reason)
            );

        $fault->appendChild( $code );
        $fault->appendChild( $reason );

        return $fault;
    }

}

?>