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
class classes_Settings_Session extends PHPUnit_Framework_TestCase
{

    public function testRead_NoValue ()
    {
        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->exactly(3) )
            ->method( "get" )
            ->will( $this->returnValue( NULL ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertNull( $settings->get('group', 'key') );
        $this->assertFalse( $settings->exists('group', 'key') );
        $this->assertSame( array(), $settings->getGroup('group') );
    }

    public function testRead_WrongType ()
    {
        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->exactly(3) )
            ->method( "get" )
            ->will( $this->returnValue( new stdClass ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertNull( $settings->get('group', 'key') );
        $this->assertFalse( $settings->exists('group', 'key') );
        $this->assertSame( array(), $settings->getGroup('group') );
    }

    public function testRead_Valid ()
    {
        $inner = new \r8\Settings\Ary(array('group' => array('key' => 'value')));

        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->exactly(6) )
            ->method( "get" )
            ->will( $this->returnValue( $inner ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertSame( 'value', $settings->get('group', 'key') );
        $this->assertTrue( $settings->exists('group', 'key') );
        $this->assertSame( array('key' => 'value'), $settings->getGroup('group') );

        $this->assertNull( $settings->get('other', 'index') );
        $this->assertFalse( $settings->exists('other', 'index') );
        $this->assertSame( array(), $settings->getGroup('other') );
    }

    public function testSet_NoValue ()
    {
        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->once() )
            ->method( "get" )
            ->will( $this->returnValue( NULL ) );
        $session->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo(
                new \r8\Settings\Ary(
                    array('group' => array('key' => 'value'))
                )
            ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertSame( $settings, $settings->set( 'group', 'key', 'value' ) );
    }

    public function testSet_WithInitial ()
    {
        $inner = new \r8\Settings\Ary( array('group' => array('one' => 'first')) );

        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->once() )
            ->method( "get" )
            ->will( $this->returnValue( $inner ) );
        $session->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo(
                new \r8\Settings\Ary(
                    array('group' => array('one' => 'first', 'key' => 'value'))
                )
            ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertSame( $settings, $settings->set( 'group', 'key', 'value' ) );
    }

    public function testDelete_NoValue ()
    {
        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->once() )
            ->method( "get" )
            ->will( $this->returnValue( NULL ) );
        $session->expects( $this->never() )->method( "set" );

        $settings = new \r8\Settings\Session( $session );

        $this->assertSame( $settings, $settings->delete( 'group', 'key' ) );
    }

    public function testDelete_WithInitial ()
    {
        $inner = new \r8\Settings\Ary( array(
            'group' => array('one' => 'first', 'key' => 'value')
        ));

        $session = $this->getMock('\r8\Session\Value', array(), array(), '', FALSE);
        $session->expects( $this->once() )
            ->method( "get" )
            ->will( $this->returnValue( $inner ) );
        $session->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo(
                new \r8\Settings\Ary( array(
                    'group' => array('one' => 'first')
                ))
            ) );

        $settings = new \r8\Settings\Session( $session );

        $this->assertSame( $settings, $settings->delete( 'group', 'key' ) );
    }

}

?>