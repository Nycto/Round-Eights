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
 * @package Log
 */

namespace r8\Log\Node;

/**
 * Disperses a log message between a list of other nodes. All the contained nodes
 * will receive the message
 */
class Hub implements \r8\iface\Log\Node
{

    /**
     * The list of nodes to send the message to
     *
     * @var Array
     */
    private $nodes;

    /**
     * Constructor...
     *
     * @param \r8\iface\Log\Node $node... The list of nodes to send the
     *  message to
     */
    public function __construct ( \r8\iface\Log\Node $node )
    {
        $this->nodes = \array_filter( func_get_args(), function ($node) {
            return $node instanceof \r8\iface\Log\Node;
        });
    }

    /**
     * @see \r8\iface\Log\Node::dispatch
     */
    public function dispatch ( \r8\Log\Message $message )
    {
        foreach ( $this->nodes as $node ) {
            $node->dispatch( $message );
        }
        return $this;
    }

}

?>