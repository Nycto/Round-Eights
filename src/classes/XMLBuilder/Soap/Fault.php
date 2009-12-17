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
 * Generates a soap fault node structure
 */
class Fault implements \r8\iface\XMLBuilder
{

    /**
     * The fault whose XML is being built
     *
     * @var \r8\Soap\Fault
     */
    private $fault;

    /**
     * The namespace URI to use for the soap elements
     *
     * @var String
     */
    private $soapURI;

    /**
     * Constructor...
     *
     * @param \r8\Soap\Fault $fault The fault whose XML is being built
     * @param String $soapURI The namespace URI to use for the soap elements
     */
    public function __construct ( \r8\Soap\Fault $fault, $soapURI )
    {
        $this->fault = $fault;
        $this->soapURI = (string) $soapURI;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $data = array(
            "Code" => array( "Value" => $this->fault->getPrimeCode() ),
            "Reason" => array( "Text" => $this->fault->getMessage() )
        );

        $parent =& $data[ 'Code' ];

        foreach ( $this->fault->getSubCodes() AS $subcode )
        {
            $parent[  'Subcode' ] = array( "Value" => $subcode );
            $parent =& $parent[ 'Subcode' ];
        }

        if ( !\r8\isEmpty($this->fault->getRole()) )
            $data['Role'] = $this->fault->getRole();

        if ( !\r8\isEmpty($this->fault->getDetails()) )
            $data['Details'] = $this->fault->getDetails();

        $builder = new \r8\XMLBuilder\Quick\Values( "Fault", $data, $this->soapURI );

        return $builder->buildNode( $doc );
    }

}

?>