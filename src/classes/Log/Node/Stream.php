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
 * Writes a log message to an output stream
 */
class Stream implements \r8\iface\Log\Node
{

    /**
     * The stream to write to
     *
     * @var \r8\iface\Stream\Out
     */
    private $stream;

    /**
     * Constructor...
     *
     * @param \r8\iface\Stream\Out $stream The stream to write to
     */
    public function __construct ( \r8\iface\Stream\Out $stream )
    {
        $this->stream = $stream;
    }

    /**
     * @see \r8\iface\Log\Node::dispatch
     */
    public function dispatch ( \r8\Log\Message $message )
    {
        $this->stream->write( $message->__toString() );
        return $this;
    }

}

