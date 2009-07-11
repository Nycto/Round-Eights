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
 * Creates new element and appends appends the results of another builder to it
 */
class Wrap extends \h2o\XMLBuilder\Node
{

    /**
     * The builder to use for generating a node
     *
     * @var \h2o\iface\XMLBuilder
     */
    private $builder;

    /**
     * Constructor...
     *
     * @param \h2o\iface\XMLBuilder $builder The builder to use for generating a node
     * @param String $tag The name of the tag to construct
     * @param Array $attrs Any attributes to add to the created element
     */
    public function __construct ( \h2o\iface\XMLBuilder $builder, $tag, array $attrs = array() )
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
                \h2o\XMLBuilder::buildNode( $this->builder, $doc )
            );

        return $node;
    }

}

?>