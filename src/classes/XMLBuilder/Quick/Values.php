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
     * The name of the root tag
     *
     * @var String
     */
    private $tag;

    /**
     * The source data to generate the XML tree from
     *
     * @var Mixed
     */
    private $data;

    /**
     * The namespace uri to use for the generated nodes
     *
     * @var String
     */
    private $namespace;

    /**
     * Constructor...
     *
     * @param String $tag The name of the root tag
     * @param Mixed $data The source data to generate the XML tree from
     * @param String $namespace The namespace uri to use for the generated nodes
     */
    public function __construct ( $tag, $data, $namespace = null )
    {
        $this->tag = $tag;
        $this->data = $data;
        $this->namespace = $namespace;
    }

    /**
     * Creates a tag with the given tag name in the proper namespace
     *
     * @param \DOMDocument $doc The document to create the node under
     * @param String The name of the tag
     * @return DOMElement
     */
    private function createElement ( \DOMDocument $doc, $tag )
    {
        $tag = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $tag);

        if ( empty($tag) )
            $tag = "unknown";

        else if ( is_numeric($tag) )
            $tag = "numeric_". $tag;

        if ( empty($this->namespace) )
            return $doc->createElement( $tag );
        else
            return $doc->createElementNS( $this->namespace, $tag );
    }

    /**
     * Iterates over a set of data and builds it as XML
     *
     * @param \DOMDocument $doc The document being built
     * @param \DOMNode $parent The parent whose children are being built
     * @param Array|\Traversable $data An array or a traversable object
     * @return NULL
     */
    private function iterate ( \DOMDocument $doc, \DOMNode $parent, &$data )
    {
        foreach ( $data AS $key => $value ) {
            $child = $this->createElement( $doc, $key );
            $parent->appendChild( $child );
            $this->build( $doc, $child, $value );
        }
    }

    /**
     * Recursively builds an XML tree
     *
     * @param \DOMDocument $doc The document being built
     * @param \DOMNode $parent The parent whose children are being built
     * @param Mixed $data The data being pieced together
     * @return NULL
     */
    private function build ( \DOMDocument $doc, \DOMNode $parent, &$data )
    {
        // Primitives
        if ( \h2o\isBasic( $data ) && $data !== NULL ) {
            $parent->appendChild(
                $doc->createTextNode( (string) $data )
            );
        }

        // Handle values that can be iterated over
        else if ( is_array($data) || $data instanceof \Traversable ) {
            $this->iterate( $doc, $parent, $data );
        }

        // If an XML builder was given, handle it
        else if ( $data instanceof \h2o\iface\XMLBuilder ) {
            $parent->appendChild( $data->buildNode( $doc ) );
        }

        // For other objects...
        else if ( is_object($data) ) {

            // If it is an object that supports "toString"
            if ( \h2o\respondTo($data, "__toString") ) {
                $parent->appendChild(
                    $doc->createTextNode( $data->__toString() )
                );
            }

            // Otherwise, iterate over its public properties
            else {
                $props = get_object_vars( $data );
                $this->iterate( $doc, $parent, $props );
            }
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
        $parent = $this->createElement( $doc, $this->tag );
        $this->build( $doc, $parent, $this->data );
        return $parent;
    }

}

?>