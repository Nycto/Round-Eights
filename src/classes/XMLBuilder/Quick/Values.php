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

namespace r8\XMLBuilder\Quick;

/**
 * Generates an XML tree from a mixed type, using any key/value pairs as
 * sub-nodes and their values
 */
class Values extends \r8\XMLBuilder\Quick
{

    /**
     * Iterates over a set of data and builds it as XML
     *
     * @param \DOMDocument $doc The document being built
     * @param String $parent The tag name of the parent element
     * @param Array|\Traversable $data An array or a traversable object
     * @param Boolean $root Whether the data being parsed is at the root level
     * @return DOMNode Returns the built node
     */
    protected function iterate ( \DOMDocument $doc, $parent, &$data, $root = FALSE )
    {
        if ( !$root && is_array($data) && \r8\ary\isList($data) ) {
            $node = $doc->createDocumentFragment();
            foreach ( $data AS $value ) {
                $node->appendChild(
                    $this->build( $doc, $parent, $value, FALSE )
                );
            }
        }
        else {
            $node = $this->createElement( $doc, $parent );
            foreach ( $data AS $key => $value ) {
                $node->appendChild(
                    $this->build( $doc, $key, $value, FALSE )
                );
            }
        }

        return $node;
    }

}

?>