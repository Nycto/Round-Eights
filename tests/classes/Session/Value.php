<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Session_Value extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        $sess = $this->getMock('h2o\iface\Session');

        try {
            new \h2o\Session\Value( "   ", $sess );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid key", $err->getMessage() );
        }
    }

    public function testGet ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("idx") )
            ->will( $this->returnValue("Data") );

        $value = new \h2o\Session\Value( "idx", $sess );

        $this->assertSame( "Data", $value->get() );
    }

    public function testSet ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("idx"), $this->equalTo("Data") )
            ->will( $this->returnValue("Data") );

        $value = new \h2o\Session\Value( "idx", $sess );

        $this->assertSame( $value, $value->set( "Data" ) );
    }

}

?>