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
 * unit tests
 */
class classes_Log_Node_Ternary extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test matcher
     *
     * @return \r8\iface\Log\Matcher
     */
    public function getTestMatcher ( $result )
    {
        $matcher = $this->getMock('\r8\iface\Log\Matcher');
        $matcher->expects( $this->once() )->method( "matches" )
            ->with( $this->isInstanceOf('\r8\Log\Message') )
            ->will( $this->returnValue((bool) $result));
        return $matcher;
    }

    public function testOne ()
    {
        $message = new \r8\Log\Message("Msg", "Error", 123);

        $one = $this->getMock('\r8\iface\Log\Node');
        $one->expects( $this->once() )->method( "dispatch" )
            ->with( $this->equalTo( $message ) );

        $two = $this->getMock('\r8\iface\Log\Node');
        $two->expects( $this->never() )->method( "dispatch" );

        $gate = new \r8\Log\Node\Ternary(
            $this->getTestMatcher(TRUE), $one, $two
        );

        $this->assertSame( $gate, $gate->dispatch($message) );
    }

    public function testFail ()
    {
        $message = new \r8\Log\Message("Msg", "Error", 123);

        $one = $this->getMock('\r8\iface\Log\Node');
        $one->expects( $this->never() )->method( "dispatch" );

        $two = $this->getMock('\r8\iface\Log\Node');
        $two->expects( $this->once() )->method( "dispatch" )
            ->with( $this->equalTo( $message ) );

        $gate = new \r8\Log\Node\Ternary(
            $this->getTestMatcher(FALSE), $one, $two
        );

        $this->assertSame( $gate, $gate->dispatch($message) );
    }

}

