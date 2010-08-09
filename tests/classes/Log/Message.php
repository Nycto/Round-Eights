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
class classes_Log_Message extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $message = new \r8\Log\Message(
            'Test Message', 'Error', 1234, array( 'key' => 'value' )
        );

        $this->assertSame( 'Test Message', $message->getMessage() );
        $this->assertSame( 'Error', $message->getLevel() );
        $this->assertSame( '1234', $message->getCode() );
        $this->assertEquals( array( 'key' => 'value' ), $message->getData() );
        $this->assertGreaterThan( time() - 5, $message->getTime() );

        $this->assertType( 'array', $message->getBacktrace() );
        $this->assertGreaterThan( 0, count($message->getBacktrace()) );
    }

    public function testConstruct_WithoutData ()
    {
        $message = new \r8\Log\Message( 'Test Message', 'Error', 1234 );
        $this->assertEquals( array(), $message->getData() );
    }

    public function testSetData ()
    {
        $message = new \r8\Log\Message( 'Test Message', 'Error', 1234 );

        $this->assertSame( $message, $message->setData('k', 'v') );
        $this->assertEquals( array('k' => 'v'), $message->getData() );

        $this->assertSame( $message, $message->setData('one', 1) );
        $this->assertEquals(
            array('k' => 'v', 'one' => 1),
            $message->getData()
        );
    }

}

?>