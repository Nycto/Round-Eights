<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Validators
 */

namespace h2o\Validator;

/**
 * Validates an e-mail address
 *
 * This validator is much looser than the RFC. There are RFC compatible e-mail
 * addresses that will not make it through this test. However, this will do
 * a good job at accepting most real world e-mail addresses. It will also
 * try to spit out an error that describes what the specific problem is.
 *
 * If it is *really* needed, perhaps an RFC compliant flag could be added
 * to the constructor.
 *
 * Information was taken from wikipedia:
 * http://en.wikipedia.org/wiki/Email_address
 *
 * As well as the following article:
 * http://www.hm2k.com/posts/what-is-a-valid-email-address
 */
class Email extends \h2o\Validator
{

    /**
     * Validates an e-mail address
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        $value = \h2o\strval( $value );

        if ( \h2o\isEmpty($value) )
            return "Email Address must not be empty";

        $atCount = substr_count($value, "@");
        if ( $atCount == 0 )
            return "Email Address must contain an 'at' (@) symbol";

        if ( $atCount > 1 )
            return "Email Address must only contain one 'at' (@) symbol";

        if ( \h2o\str\contains(" ", $value) )
            return "Email Address must not contain spaces";

        if ( \h2o\str\contains("\n", $value) || \h2o\str\contains("\r", $value) )
            return "Email Address must not contain line breaks";

        if ( \h2o\str\contains("\t", $value) )
            return "Email Address must not contain tabs";

        if ( preg_match('/\.\.+/', $value) )
            return "Email Address must not contain repeated periods";

        if ( preg_match('/[^a-z0-9'. preg_quote('!#$%&\'*+-/=?^_`{|}~@.[]', '/') .']/i', $value) )
            return "Email Address contains invalid characters";

        if ( \h2o\str\endsWith($value, ".") )
            return "Email Address must not end with a period";


        list( $local, $domain ) = explode("@", $value);

        if ( \h2o\str\startsWith($local, ".") )
            return "Email Address must not start with a period";

        // This is hard to describe to a user, so just give them a vague description
        if ( \h2o\str\endsWith($local, ".") )
            return "Email Address is not valid";

        if ( strlen($local) > 64 || strlen($domain) > 255 )
            return "Email Address is too long";

        $regex = '/'
            .'^'
            .'[\w!#$%&\'*+\/=?^`{|}~.-]+'
            .'@'
            .'(?:[a-z\d][a-z\d-]*(?:\.[a-z\d][a-z\d-]*)?)+'
            .'\.(?:[a-z][a-z\d-]+)'
            .'$'
            .'/iD';

        // Do a final regex to match the basic form
        if ( !preg_match($regex, $value) )
            return "Email Address is not valid";

    }

}

?>