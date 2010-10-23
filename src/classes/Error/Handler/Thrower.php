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
 * A Handler to throw exceptions when possible
 */
class Thrower implements \r8\iface\Error\Handler
{

    /**
     * The handler being wrapped
     *
     * @var \r8\iface\Error\Handler
     */
    private $handler;

    /**
     * Constructor...
     *
     * @param \r8\iface\Error\Handler $handler The handler being wrapped
     */
    public function __construct ( \r8\iface\Error\Handler $handler = NULL )
    {
        $this->handler = $handler;
    }

    /**
     * Handles an error
     *
     * @param \r8\iface\Error $error The error to handle
     * @return NULL
     */
    public function handle ( \r8\iface\Error $error )
    {
        if ( $this->handler )
            $this->handler->handle( $error );

        throw new \ErrorException(
            $error->getMessage(), $error->getCode(), $error->getCode(),
            $error->getFile(), $error->getLine()
        );
    }

}

