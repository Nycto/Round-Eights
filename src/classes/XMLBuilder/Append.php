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

namespace r8\XMLBuilder;

/**
 * Builds a parent node, then builds a set of children and appends them to the parent
 */
class Append implements \r8\iface\XMLBuilder
{

    /**
     * The builder to use for constructing the parent element
     *
     * @var \r8\iface\XMLBuilder
     */
    private $parent;

    /**
     * The builders to use for generating the child nodes
     *
     * @var array An array of \r8\iface\XMLBuilder object
     */
    private $children = array();

    /**
     * Constructor...
     *
     * @param \r8\iface\XMLBuilder $parent The builder to use for constructing
     *      the parent element
     */
    public function __construct ( \r8\iface\XMLBuilder $parent )
    {
        $this->parent = $parent;
    }

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
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $parent = \r8\XMLBuilder::buildNode( $this->parent, $doc );

        foreach ( $this->children AS $child ) {

            $parent->appendChild(
                    \r8\XMLBuilder::buildNode( $child, $doc )
                );

        }

        return $parent;
    }

}

