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
 * Asserts that the code of a message starts with any of the given strings
 */
class CodeStartsWith implements \r8\iface\Log\Matcher
{

    /**
     * The list of strings the code can start with
     *
     * @var Array
     */
    private $codes;

    /**
     * Constructor...
     *
     * @param Array $codes The list of strings the code can start with
     */
    public function __construct ( array $codes )
    {
        $this->codes = \r8\ary\stringize($codes);
    }

    /**
     * @see \r8\iface\Log\Matcher::matches
     */
    public function matches ( \r8\Log\Message $message )
    {
        $message = $message->getCode();
        foreach ( $this->codes as $code )
        {
            if ( \r8\str\startsWith($message, $code) )
                return TRUE;
        }
        return FALSE;
    }

}

