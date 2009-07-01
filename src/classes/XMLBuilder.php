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

namespace h2o;

/**
 * Root class for working with XMLBuilders
 *
 * This unifies an XMLBuilder and a DOMDocument into a single object, and allows
 * you to initiate the build process.
 */
class XMLBuilder
{

    /**
     * The DOMDocument to add the built nodes to
     *
     * @var \DOMDocument $doc
     */
    private $doc;

    /**
     * The XML Builder that will construct the root node to attach to the document
     *
     * @var \h2o\iface\XMLBuilder
     */
    private $builder;

    /**
     * Ensures that a node is a member of the given document
     *
     * If the node already belongs to the doc, nothing will be done. Otherwise,
     * it will be imported into the document (a deep import).
     *
     * @param DOMDocument $doc The dom document this node should belong to
     * @param DOMNode $node The node being tested
     * @return DOMNode Returns the imported dom node
     */
    static public function importNode ( \DOMDocument $doc, \DOMNode $node )
    {
        // If there is no owner, or it has a different owner, import it
        if ( is_null($node->ownerDocument) || !$node->ownerDocument->isSameNode( $doc ) )
           $node = $doc->importNode( $node, TRUE );

        return $node;
    }

    /**
     * A helper function for building a node, ensuring a proper value was returned,
     * and then importing it into the document
     */
    static public function buildNode ( \h2o\iface\XMLBuilder $builder, \DOMDocument $doc )
    {
        $built = $builder->buildNode( $doc );

        if ( !($built instanceof \DOMNode) ) {
            $err = new \h2o\Exception\Interaction("XMLBuilder did not return a DOMNode object");
            $err->addData("Document", \h2o\getDump($doc));
            $err->addData("Built Node", \h2o\getDump($built));
            throw $err;
        }

        // Ensure the built node is a member of the document
        return \h2o\XMLBuilder::importNode( $doc, $built );
    }

    /**
     * Constructor...
     *
     * @param DOMDocument $doc The DOMDocument to add the built nodes to
     * @param \h2o\iface\XMLBuilder $builder The XML Builder that will construct
     *      the root node to attach to the document
     */
    public function __construct ( \DOMDocument $doc, \h2o\iface\XMLBuilder $builder )
    {
        $this->doc = $doc;
        $this->builder = $builder;
    }

    /**
     * Invokes the build process and returns the generated DOMDocument
     *
     * @return \DOMDocument $doc Returns the DOMDocument that was given on
     *      construction, but with the built nodes attached
     */
    public function buildDoc ()
    {
        $this->doc->appendChild(
                self::buildNode( $this->builder, $this->doc )
            );

        return $this->doc;
    }

}

?>