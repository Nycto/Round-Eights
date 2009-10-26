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

namespace h2o\XMLBuilder\Soap;

/**
 * Generates a soap fault node structure
 */
class Fault implements \h2o\iface\XMLBuilder
{

    /**
     * The fault whose XML is being built
     *
     * @var \h2o\Soap\Fault
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
     * @param \h2o\Soap\Fault $fault The fault whose XML is being built
     * @param String $soapURI The namespace URI to use for the soap elements
     */
    public function __construct ( \h2o\Soap\Fault $fault, $soapURI )
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

        if ( !\h2o\isEmpty($this->fault->getRole()) )
            $data['Role'] = $this->fault->getRole();

        if ( !\h2o\isEmpty($this->fault->getDetails()) )
            $data['Details'] = $this->fault->getDetails();

        $builder = new \h2o\XMLBuilder\Quick\Values( "Fault", $data, $this->soapURI );

        return $builder->buildNode( $doc );
    }

}

?>