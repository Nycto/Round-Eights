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
 * @package XMLBuilder
 */

namespace r8\Soap;

/**
 * The base class for the Header or Message nodes in a Soap message
 */
abstract class Node
{

    /**
     * The DOMElement this node represents
     *
     * @var DOMElement
     */
    protected $node;

    /**
     * The namespace URI to use for any soap envelope elements
     *
     * @var String
     */
    protected $soapNS;

    /**
     * Constructor...
     *
     * @param DOMElement $node The DOM Element this node represents
     * @param String $soapNS The namespace URI to use for any soap
     * 		envelope elements
     */
    public function __construct ( \DOMElement $node, $soapNS )
    {
        $this->node = $node;
        $this->soapNS = trim( (string) $soapNS );
    }

    /**
     * Returns the DOMElement this node represents
     *
     * @return DOMElement
     */
    public function getElement ()
    {
        return $this->node;
    }

    /**
     * Returns the Namespace that will be used to search for soap envelope nodes
     *
     * @return String
     */
    public function getSoapNS ()
    {
        return $this->soapNS;
    }

    /**
     * Returns the name of the tag for this node
     *
     * @return String
     */
    public function getTag ()
    {
        return preg_replace( '/.*?:/', '', $this->node->tagName );
    }

    /**
     * Returns the Namespace tag prefix of this element
     *
     * @return String|NULL Returns NULL if there is no prefix
     */
    public function getPrefix ()
    {
        $tagName = ltrim( $this->node->tagName, ":" );

        if ( !\r8\str\contains(":", $tagName) )
            return NULL;

        return strstr( $tagName, ":", TRUE );
    }

    /**
     * Returns the Namespace URI of this element
     *
     * @return String|NULL Returns NULL if there is no Namespace
     */
    public function getNamespace ()
    {
        return $this->node->lookupnamespaceURI( $this->getPrefix() );
    }

}

?>