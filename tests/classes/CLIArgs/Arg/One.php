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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * Unit Tests
 */
class classes_CLIArgs_Arg_One extends PHPUnit_Framework_TestCase
{

    public function testConsume ()
    {
        $filter = $this->getMock('\r8\iface\Filter');
        $filter->expects( $this->once() )->method( "filter" )
            ->with( $this->equalTo( "input" ) )
            ->will( $this->returnValue( "filtered" ) );

        $validator = $this->getMock('\r8\iface\Validator');
        $validator->expects( $this->once() )->method( "ensure" )
            ->with( $this->equalTo( "filtered" ) );

        $input = $this->getMock('\r8\CLIArgs\Input', array(), array(array()));
        $input->expects( $this->once() )->method( "popArgument" )
            ->will( $this->returnValue( "input" ) );

        $arg = new \r8\CLIArgs\Arg\One( "test", $filter, $validator );

        $this->assertSame( array("filtered"), $arg->consume($input) );
    }

}

?>