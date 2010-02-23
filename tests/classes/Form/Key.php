<?php
/**
 * Unit Test File
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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Form_key extends PHPUnit_Framework_TestCase
{

    public function testValue ()
    {
        $field = new \r8\Form\Key("fld", "Unit Test");

        $this->assertThat( $field->getValue(), $this->isType("string") );
        $this->assertSame( 20, strlen( $field->getValue() ) );
        $this->assertRegExp( '/^[0-9a-z]+$/i', $field->getValue() );
    }

    public function testValidator ()
    {
        $field = new \r8\Form\Key("fld", "Unit Test");

        $this->assertThat(
                $field->getValidator(),
                $this->isInstanceOf("r8\Validator\Compare")
            );
    }

    public function testValid ()
    {
        $field = new \r8\Form\Key("fld", "Unit Test");

        $this->assertThat( $field->getValue(), $this->isType("string") );
        $this->assertSame( 20, strlen( $field->getValue() ) );

        $this->assertTrue( $field->isValid() );
    }

    public function testInvalid ()
    {
        $field = new \r8\Form\Key("fld", "Unit Test");
        $field->setValue("This isn't it");

        $this->assertFalse( $field->isValid() );

        $result = $field->validate();
        $this->assertEquals(
                array("This form has expired"),
                $result->getErrors()
            );
    }

}

?>