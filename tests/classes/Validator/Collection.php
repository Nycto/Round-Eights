<?php
/**
 * Unit Test File
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
 * @package UnitTests
 */


require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_validator_collection extends PHPUnit_Framework_TestCase
{

    public function testAddObject ()
    {
        $collection = $this->getMock("cPHP\Validator\Collection", array("process"));

        $valid = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(array( $valid ), $list->get());
    }

    public function testAddObjectError ()
    {
        $this->setExpectedException("\cPHP\Exception\Argument");

        $collection = $this->getMock("cPHP\Validator\Collection", array("process"));
        $valid = $this->getMock("stub_random_class");

        $collection->add($valid);
    }

    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("cPHP\Validator\Collection", array("process"));

        $valid = get_class( $this->getMock("cPHP\iface\Validator", array("validate", "isValid")) );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }

    public function testAddClassString ()
    {
        $collection = $this->getMock("cPHP\Validator\Collection", array("process"));

        $valid = get_class( $collection );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }

    public function testAddStringError ()
    {
        $this->setExpectedException("\cPHP\Exception\Argument");

        $collection = $this->getMock("cPHP\Validator\Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );

        $collection->add($valid);
    }

    public function testAddMany ()
    {
        $collection = $this->getMock( "\cPHP\Validator\Collection", array("process") );

        $valid = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));

        $this->assertSame(
                $collection,
                $collection->addMany( array( $valid, "Non validator" ), array(), $valid2 )
            );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());

    }

    public function testConstruct ()
    {

        $valid = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP\iface\Validator", array("validate", "isValid"));

        $collection = $this->getMock(
                "\cPHP\Validator\Collection",
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );


        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());

    }

}

?>