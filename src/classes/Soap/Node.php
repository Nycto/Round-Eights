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
 * The base class for the Header or Message nodes in a Soap message
 */
abstract class Node
{

    /**
     * The DOMElement this node represents
     *
     * @var DOMElement
     */
    private $node;

    /**
     * Constructor...
     *
     * @param DOMElement $node The DOM Element this node represents
     */
    public function __construct ( \DOMElement $node )
    {
        $this->node = $node;
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

        if ( !\h2o\str\contains(":", $tagName) )
            return NULL;

        return strstr( $tagName, ":", TRUE );
    }

    /**
     * Returns the Namespace URI of this element
     *
     * @return String
     */
    public function getNamespace ()
    {
        return $this->node->lookupnamespaceURI( $this->getPrefix() );
    }

}

?>