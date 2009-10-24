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

namespace h2o\XMLBuilder\Quick;

/**
 * Generates an XML tree from a mixed type, using any key/value pairs as
 * sub-nodes and their values
 */
class Values implements \h2o\iface\XMLBuilder
{

    /**
     * The source data to generate the XML tree from
     *
     * @var Mixed
     */
    private $source;

    /**
     * Constructor...
     *
     * @param Mixed $source The source data to generate the XML tree from
     */
    public function __construct ( $source )
    {
        $this->source = $source;
    }

    /**
     * Recursively builds an XML tree
     *
     * @param \DOMDocument $doc The document being built
     * @param \DOMNode $parent The parent whose children are being built
     * @param Mixed $data The data being pieced together
     * @return NULL
     */
    private function iterate ( \DOMDocument $doc, \DOMNode $parent, &$data )
    {
        foreach ( $data AS $key => $value )
        {
            $node = null;

            // If an XML builder was given, handle it
            if ( $value instanceof \h2o\iface\XMLBuilder ) {
                $node = $doc->createElement( $key );
                $node->appendChild( $value->buildNode( $doc ) );
            }

            // For other objects...
            else if ( is_object($value) ) {

                // If it is an object that supports "toString"
                if ( \h2o\respondTo($value, "__toString") )
                    $node = $doc->createTextNode( $value->__toString() );

                else
                    $value = get_object_vars( $value );
            }

            // Handle values that can be iterated over
            if ( is_array($value) || $value instanceof \Traversable ) {
                $node = $doc->createElement( $key );
                $this->iterate( $doc, $node, $value );
            }

            // Primitives
            else if ( \h2o\isBasic( $value ) ) {
                $node = $doc->createElement( $key, (string) $value );
            }

            if ( !empty($node) )
                $parent->appendChild( $node );
        }
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        // If an XML builder was given, handle it
        if ( $this->source instanceof \h2o\iface\XMLBuilder ) {
            return $this->source->buildNode( $doc );
        }

        // For other objects...
        else if ( is_object($this->source) ) {

            // If it is an object that supports "toString"
            if ( \h2o\respondTo($this->source, "__toString") )
                return $doc->createTextNode( $this->source->__toString() );

            else
                $data = get_object_vars( $this->source );
        }

        else {
            $data = $this->source;
        }

        // Handle values that can be iterated over
        if ( is_array($data) || $data instanceof \Traversable ) {
            $node = $doc->createDocumentFragment();

            $this->iterate( $doc, $node, $data );

            if( $node->childNodes->length == 1 ) {
                $first = $node->firstChild;
                $node->removeChild( $first );
                return $first;
            }

            else if ( $node->hasChildNodes() ) {
                return $node;
            }
        }

        // Primitives
        else if ( \h2o\isBasic( $data ) ) {
            return $doc->createTextNode( $data );
        }

        // For anything else, return a default value
        return $doc->createTextNode("");
    }

}

?>