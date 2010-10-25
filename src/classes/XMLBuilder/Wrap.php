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
 * Creates new element and appends appends the results of another builder to it
 */
class Wrap extends \r8\XMLBuilder\Node
{

    /**
     * The builder to use for generating a node
     *
     * @var \r8\iface\XMLBuilder
     */
    private $builder;

    /**
     * Constructor...
     *
     * @param \r8\iface\XMLBuilder $builder The builder to use for generating a node
     * @param String $tag The name of the tag to construct
     * @param Array $attrs Any attributes to add to the created element
     */
    public function __construct ( \r8\iface\XMLBuilder $builder, $tag, array $attrs = array() )
    {
        parent::__construct($tag, $attrs);
        $this->builder = $builder;
    }

    /**
     * Creates and returns a new node to attach to a document
     *
     * @param \DOMDocument $doc The root document this node is being created for
     * @return \DOMElement Returns the created node
     */
    public function buildNode ( \DOMDocument $doc )
    {
        $node = parent::buildNode( $doc );

        $node->appendChild(
                \r8\XMLBuilder::buildNode( $this->builder, $doc )
            );

        return $node;
    }

}

