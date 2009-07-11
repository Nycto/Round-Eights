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

namespace h2o\XMLBuilder;

/**
 * Builds a parent node, then builds a set of children and appends them to the parent
 */
class Append implements \h2o\iface\XMLBuilder
{

    /**
     * The builder to use for constructing the parent element
     *
     * @var \h2o\iface\XMLBuilder
     */
    private $parent;

    /**
     * The builders to use for generating the child nodes
     *
     * @var array An array of \h2o\iface\XMLBuilder object
     */
    private $children = array();

    /**
     * Constructor...
     *
     * @param \h2o\iface\XMLBuilder $parent The builder to use for constructing
     *      the parent element
     */
    public function __construct ( \h2o\iface\XMLBuilder $parent )
    {
        $this->parent = $parent;
    }

    /**
     * Adds a new builder whose results will be appended to the parent
     *
     * @param \h2o\iface\XMLBuilder $child The builder to use to construct this
     *      child element
     * @return \h2o\XMLBuilder\Append Returns a self reference
     */
    public function addChild ( \h2o\iface\XMLBuilder $child )
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
        $parent = \h2o\XMLBuilder::buildNode( $this->parent, $doc );

        foreach ( $this->children AS $child ) {

            $parent->appendChild(
                    \h2o\XMLBuilder::buildNode( $child, $doc )
                );

        }

        return $parent;
    }

}

?>