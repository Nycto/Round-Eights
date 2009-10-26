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
 * attributes of their parent nodes
 */
class Attrs extends \h2o\XMLBuilder\Quick
{

    /**
     * Iterates over a set of data and builds it as XML
     *
     * @param \DOMDocument $doc The document being built
     * @param String $parent The tag name of the parent element
     * @param Array|\Traversable $data An array or a traversable object
     * @return DOMNode Returns the built node
     */
    protected function iterate ( \DOMDocument $doc, $parent, &$data )
    {
        $node = $this->createElement( $doc, $parent );

        foreach ( $data AS $key => $value ) {

            if ( \h2o\isEmpty($value) ) {
                continue;
            }

            // Primitives
            else if ( \h2o\isBasic( $value ) && $value !== NULL ) {
                $node->setAttribute(
                    self::normalizeName( $key ),
                    (string) $value
                );
            }

            // Handle values that can be iterated over
            else if ( is_array($value) || $value instanceof \Traversable ) {
                $node->appendChild( $this->iterate( $doc, $key, $value ) );
            }

            // If an XML builder was given, handle it
            else if ( $value instanceof \h2o\iface\XMLBuilder ) {
                $child = $this->createElement( $doc, $key );
                $child->appendChild( $value->buildNode( $doc ) );
                $node->appendChild( $child );
            }

            // For other objects...
            else if ( is_object($value) ) {

                // If it is an object that supports "toString"
                if ( \h2o\respondTo($value, "__toString") ) {
                    $node->setAttribute(
                        self::normalizeName( $key ),
                        $value->__toString()
                    );
                }

                // Otherwise, iterate over its public properties
                else {
                    $props = get_object_vars( $value );
                    $node->appendChild( $this->iterate( $doc, $key, $props ) );
                }
            }

        }

        return $node;
    }

}

?>