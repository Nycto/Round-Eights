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

namespace r8\XMLBuilder;

/**
 * Builds a list of children and collects them into a document fragment
 */
class Series implements \r8\iface\XMLBuilder
{

    /**
     * The builders to use for generating the child nodes
     *
     * @var array An array of \r8\iface\XMLBuilder object
     */
    private $children = array();

    /**
     * Adds a new builder whose results will be appended to the parent
     *
     * @param \r8\iface\XMLBuilder $child The builder to use to construct this
     *      child element
     * @return \r8\XMLBuilder\Append Returns a self reference
     */
    public function addChild ( \r8\iface\XMLBuilder $child )
    {
        $this->children[] = $child;
        return $this;
    }

    /**
     * Returns the child XMLBuilders registered in this instance
     *
     * @return array Return an array of \r8\iface\XMLBuilder objects
     */
    public function getChildren ()
    {
        return $this->children;
    }

    /**
     * Returns whether there are any children registered in this builder
     *
     * @return Boolean
     */
    public function hasChildren ()
    {
        return count( $this->children ) > 0;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $parent = $doc->createDocumentFragment();

        foreach ( $this->children AS $child ) {

            $child = \r8\XMLBuilder::buildNode( $child, $doc );

            if ( $child instanceof \DOMNode )
                $parent->appendChild( $child );
        }

        if ( !$parent->hasChildNodes() )
            return $doc->createTextNode("");

        return $parent;
    }

}

?>