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
class classes_Validator_Collection extends PHPUnit_Framework_TestCase
{

    public function testAddObject ()
    {
        $collection = $this->getMock("r8\Validator\Collection", array("process"));

        $valid = $this->getMock("r8\iface\Validator");

        $this->assertSame( $collection, $collection->add($valid) );

        $this->assertEquals(array( $valid ), $collection->getValidators());
    }

    public function testAddObjectError ()
    {
        $this->setExpectedException('\r8\Exception\Argument');

        $collection = $this->getMock("r8\Validator\Collection", array("process"));
        $valid = $this->getMock("stub_random_class");

        $collection->add($valid);
    }

    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("r8\Validator\Collection", array("process"));

        $valid = get_class( $this->getMock("r8\iface\Validator") );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list[0], $this->isInstanceOf( $valid ) );
    }

    public function testAddClassString ()
    {
        $collection = $this->getMock("r8\Validator\Collection", array("process"));

        $valid = get_class( $collection );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list[0], $this->isInstanceOf( $valid ) );
    }

    public function testAddStringError ()
    {
        $this->setExpectedException('\r8\Exception\Argument');

        $collection = $this->getMock("r8\Validator\Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );

        $collection->add($valid);
    }

    public function testAddMany ()
    {
        $collection = $this->getMock( '\r8\Validator\Collection', array("process") );

        $valid = $this->getMock("r8\iface\Validator");
        $valid2 = $this->getMock("r8\iface\Validator");

        $this->assertSame(
                $collection,
                $collection->addMany( array( $valid, "Non validator" ), array(), $valid2 )
            );

        $this->assertEquals(array( $valid, $valid2 ), $collection->getValidators());
    }

    public function testConstruct ()
    {

        $valid = $this->getMock("r8\iface\Validator");
        $valid2 = $this->getMock("r8\iface\Validator");

        $collection = $this->getMock(
                '\r8\Validator\Collection',
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );

        $this->assertEquals(array( $valid, $valid2 ), $collection->getValidators());

    }

}

?>