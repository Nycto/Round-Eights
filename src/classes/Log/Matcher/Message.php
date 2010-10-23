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

namespace r8\Log\Matcher;

/**
 * Uses a validator to match the message string of a log message
 */
class Message implements \r8\iface\Log\Matcher
{

    /**
     * The validator to match against
     *
     * @var \r8\iface\Validator
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param \r8\iface\Validator $validator
     */
    public function __construct ( \r8\iface\Validator $validator )
    {
        $this->validator = $validator;
    }

    /**
     * @see \r8\iface\Log\Matcher::matches
     */
    public function matches ( \r8\Log\Message $message )
    {
        return $this->validator->isValid( $message->getMessage() );
    }

}

