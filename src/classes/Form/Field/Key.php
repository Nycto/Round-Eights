<?php
/**
 * A hidden field used to help prevent XSRF attacks
 *
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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Forms
 */

namespace r8\Form\Field;

/**
 * A specialized hidden field used to help prevent XSRF attacks
 *
 * This works by generating a form "Key" for each user. The key is kept in a
 * hidden field. The form will only validate if the key in the form matches
 * the key generated by the server.
 *
 * The key generated is 20 characters long
 */
class Key extends \r8\Form\Field\Hidden
{

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     * @param String $seed A random string that is used to help make the generated
     *      key unique to this specific instance
     */
    public function __construct( $name, $seed )
    {
        $this->setName( $name );

        $seed = \r8\reduce($seed);
        if ( empty($seed) )
            throw new \r8\Exception\Argument( 1, "Key Seed", "Must not be empty" );

        $key = substr( sha1( $seed . session_id() ), 0, 20 );

        $this->setValue( $key );

        $validator = new \r8\Validator\Compare( "==", $key );
        $validator->addError("This form has expired");

        $this->setValidator( $validator );
    }

}

?>