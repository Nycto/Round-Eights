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
 * Creates new element and returns it
 */
class Node implements \r8\iface\XMLBuilder
{

    /**
     * The name of the tag to construct
     *
     * @var String
     */
    private $tag;

    /**
     * Any attributes to add to the created element
     *
     * @var array
     */
    private $attrs = array();

    /**
     * Constructor...
     *
     * @param String $tag The name of the tag to construct
     * @param Array $attrs Any attributes to add to the created element
     */
    public function __construct ( $tag, array $attrs = array() )
    {
        $tag = trim( (string) $tag );

        if ( \r8\isEmpty($tag) )
            throw new \r8\Exception\Argument(1, "Tag Name", "Must not be empty");

        $this->tag = $tag;
        $this->setAttributes( $attrs );
    }

    /**
     * Returns the attributes that will be attached to the wrapping node after
     * it is imported into the document.
     *
     * @return array
     */
    public function getAttributes ()
    {
        return $this->attrs;
    }

    /**
     * Sets the attributes that will be attached to the wrapping node after
     * it is imported into the document.
     *
     * @param array $attrs The attribute arrays to add to the wrapping node
     * @return \r8\XMLBuilder\Wrap Returns a self reference
     */
    public function setAttributes ( array $attrs )
    {
        $this->attrs = \r8\ary\flatten( $attrs );
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
        $node = $doc->createElement( $this->tag );

        foreach ( $this->attrs AS $key => $value ) {
            $node->setAttribute($key, $value);
        }

        return $node;
    }

}

