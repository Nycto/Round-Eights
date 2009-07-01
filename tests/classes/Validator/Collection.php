<?php
/**
 * Unit Test File
 *
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
        $collection = $this->getMock("h2o\Validator\Collection", array("process"));

        $valid = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));

        $this->assertSame( $collection, $collection->add($valid) );

        $this->assertEquals(array( $valid ), $collection->getValidators());
    }

    public function testAddObjectError ()
    {
        $this->setExpectedException("\h2o\Exception\Argument");

        $collection = $this->getMock("h2o\Validator\Collection", array("process"));
        $valid = $this->getMock("stub_random_class");

        $collection->add($valid);
    }

    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("h2o\Validator\Collection", array("process"));

        $valid = get_class( $this->getMock("h2o\iface\Validator", array("validate", "isValid")) );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list[0], $this->isInstanceOf( $valid ) );
    }

    public function testAddClassString ()
    {
        $collection = $this->getMock("h2o\Validator\Collection", array("process"));

        $valid = get_class( $collection );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list[0], $this->isInstanceOf( $valid ) );
    }

    public function testAddStringError ()
    {
        $this->setExpectedException("\h2o\Exception\Argument");

        $collection = $this->getMock("h2o\Validator\Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );

        $collection->add($valid);
    }

    public function testAddMany ()
    {
        $collection = $this->getMock( "\h2o\Validator\Collection", array("process") );

        $valid = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));

        $this->assertSame(
                $collection,
                $collection->addMany( array( $valid, "Non validator" ), array(), $valid2 )
            );

        $this->assertEquals(array( $valid, $valid2 ), $collection->getValidators());
    }

    public function testConstruct ()
    {

        $valid = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("h2o\iface\Validator", array("validate", "isValid"));

        $collection = $this->getMock(
                "\h2o\Validator\Collection",
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );

        $this->assertEquals(array( $valid, $valid2 ), $collection->getValidators());

    }

}

?>