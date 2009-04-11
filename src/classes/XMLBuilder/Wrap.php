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
 * Appends the built node from an XMLBuilder to a given DOMNode
 */
class Wrap implements \cPHP\iface\XMLBuilder
{

    /**
     * The builder to use for generating a node
     *
     * @var XMLBuilder
     */
    private $builder;

    /**
     * The dom node to nest the builder in
     *
     * @var DOMNode
     */
    private $node;

    /**
     * Constructor...
     *
     * @param XMLBuilder $builder The builder to use for generating a node
     * @param DOMNode $node The dom node to nest the builder in
     */
    public function __construct ( \cPHP\iface\XMLBuilder $builder, \DOMNode $node )
    {
        $this->builder = $builder;
        $this->node = $node;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param DOMDocument $doc The root document this node is being created for
     * @return DOMNode Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $node = \cPHP\XMLBuilder::importNode( $doc, $this->node );

        $built = $this->builder->buildNode( $doc );

        if ( !($built instanceof \DOMNode) ) {
            $err = new \cPHP\Exception\Interaction("XMLBuilder did not return a DOMNode object");
            $err->addData("Document", \cPHP\getDump($doc));
            $err->addData("Built Node", \cPHP\getDump($built));
            throw $err;
        }

        $node->appendChild( $built );

        return $node;
    }

}

?>