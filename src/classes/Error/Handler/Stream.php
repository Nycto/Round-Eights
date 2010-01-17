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
 * @package Error
 */

namespace r8\Error\Handler;

/**
 * Writes an error to a stream using the specified format
 */
class Stream implements \r8\iface\Error\Handler
{

    /**
     * The formatter to use for generating a string from the error
     *
     * @var \r8\iface\Error\Formatter
     */
    private $formatter;

    /**
     * The stream to write the error to
     *
     * @var \r8\iface\Stream\Out
     */
    private $stream;

    /**
     * Constructor...
     *
     * @param \r8\iface\Error\Formatter $formatter The formatter to use for
     *      generating a string from the error
     * @param \r8\iface\Stream\Out $stream The stream to write the error to
     */
    public function __construct (
        \r8\iface\Error\Formatter $formatter,
        \r8\iface\Stream\Out $stream
    ) {
        $this->formatter = $formatter;
        $this->stream = $stream;
    }

    /**
     * Handles an error
     *
     * @param \r8\iface\Error $error The error to handle
     * @return NULL
     */
    public function handle ( \r8\iface\Error $error )
    {
        $this->stream->write(
            $this->formatter->format( $error )
        );
    }

}

?>