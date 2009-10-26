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
class Values extends \h2o\XMLBuilder\Quick
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
        if ( is_array($data) && \h2o\ary\isList($data) ) {
            $node = $doc->createDocumentFragment();
            foreach ( $data AS $value ) {
                $node->appendChild(
                    $this->build( $doc, $parent, $value )
                );
            }
        }
        else {
            $node = $this->createElement( $doc, $parent );
            foreach ( $data AS $key => $value ) {
                $node->appendChild(
                    $this->build( $doc, $key, $value )
                );
            }
        }

        return $node;
    }

}

?>