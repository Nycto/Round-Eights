<?php
/**
 * Validation class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Validators
 */

namespace cPHP\Validator;

/**
 * Validates a URL
 */
class URL extends \cPHP\Validator
{

    /**
     * Flags that a relative URL should be allowed
     */
    const ALLOW_RELATIVE = 1;

    /**
     * Any flags to pass to the isEmpty function
     */
    protected $flags = 0;

    /**
     * Constructor...
     *
     * @param Integer $flags Any flags to pass to the isEmpty function. For
     *      more details, take a look at that function
     */
    public function __construct ( $flags = 0 )
    {
        $this->flags = max( intval($flags), 0 );
    }

    /**
     * Validates a URL
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !is_string($value) )
            return "URL must be a string";

        if ( \cPHP\str\contains(" ", $value) )
            return "URL must not contain spaces";

        if ( \cPHP\str\contains("\t", $value) )
            return "URL must not contain tabs";

        if ( \cPHP\str\contains("\n", $value) || \cPHP\str\contains("\r", $value) )
            return "URL must not contain line breaks";

        if ( preg_match('/[^a-z0-9'. preg_quote('$-_.+!*\'(),{}|\\^~[]`<>#%";/?:@&=', '/') .']/i', $value) )
            return "URL contains invalid characters";

        if ( $this->flags & self::ALLOW_RELATIVE ) {

            $parsed = @parse_url( $value );

            if ( $parsed === FALSE )
                return "URL is not valid";

        }

        else if ( !filter_var( $value, FILTER_VALIDATE_URL ) ) {
            return "URL is not valid";
        }
    }

}

?>