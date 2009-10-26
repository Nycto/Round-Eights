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
 * Base class for
 */
abstract class Quick implements \h2o\iface\XMLBuilder
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
     * Prepares a tag or attribute name for use
     *
     * @param String $name The string to prepare
     * @return String
     */
    static protected function normalizeName ( $name )
    {
        $name = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $name);

        if ( \h2o\isEmpty($name) )
            $name = "unknown";

        else if ( is_numeric($name) )
            $name = "numeric_". $name;

        return $name;
    }

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
    protected function createElement ( \DOMDocument $doc, $tag )
    {
        $tag = self::normalizeName( $tag );

        if ( empty($this->namespace) )
            return $doc->createElement( $tag );
        else
            return $doc->createElementNS( $this->namespace, $tag );
    }

    /**
     * Iterates over a set of data and builds it as XML
     *
     * @param \DOMDocument $doc The document being built
     * @param String $parent The tag name of the parent element
     * @param Array|\Traversable $data An array or a traversable object
     * @param Boolean $root Whether the data being parsed is at the root level
     * @return DOMNode Returns the built node
     */
    abstract protected function iterate ( \DOMDocument $doc, $parent, &$data, $root = FALSE );

    /**
     * Recursively builds an XML tree
     *
     * @param \DOMDocument $doc The document being built
     * @param String $parent The tag name of the parent element
     * @param Mixed $data The data being pieced together
     * @param Boolean $root Whether the data being parsed is at the root level
     * 		This is used during iteration, for example, to ensure lists are
     * 		created properly
     * @return DOMNode Returns the built node
     */
    protected function build ( \DOMDocument $doc, $parent, &$data, $root = FALSE )
    {
        if ( \h2o\isEmpty($data) ) {
            return $this->createElement( $doc, $parent );
        }

        // Primitives
        else if ( \h2o\isBasic( $data ) && $data !== NULL ) {
            $node = $this->createElement( $doc, $parent );
            $node->appendChild(
                $doc->createTextNode( (string) $data )
            );
        }

        // Handle values that can be iterated over
        else if ( is_array($data) || $data instanceof \Traversable ) {
            $node = $this->iterate( $doc, $parent, $data, $root );
        }

        // If an XML builder was given, handle it
        else if ( $data instanceof \h2o\iface\XMLBuilder ) {
            $node = $this->createElement( $doc, $parent );
            $node->appendChild(
                \h2o\XMLBuilder::buildNode( $data, $doc )
            );
        }

        // For other objects...
        else if ( is_object($data) ) {

            // If it is an object that supports "toString"
            if ( \h2o\respondTo($data, "__toString") ) {
                $node = $this->createElement( $doc, $parent );
                $node->appendChild(
                    $doc->createTextNode( $data->__toString() )
                );
            }

            // Otherwise, iterate over its public properties
            else {
                $props = get_object_vars( $data );
                $node = $this->iterate( $doc, $parent, $props, $root );
            }
        }

        return $node;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        return $this->build( $doc, $this->tag, $this->data, TRUE );
    }

}

?>