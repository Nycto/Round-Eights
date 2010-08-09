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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Log extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a Log Node that expects dispatch to be called once with
     * the given error level
     *
     * @return \r8\iface\Log\Node
     */
    public function getLogNode ( $level )
    {
        $self = $this;

        $inner = $this->getMock('\r8\iface\Log\Node');
        $inner->expects( $this->once() )->method( "dispatch" )
            ->with( $this->isInstanceOf('\r8\Log\Message') )
            ->will( $this->returnCallback(function ($msg) use ($level, $self) {
                $self->assertSame( $level, $msg->getLevel() );
                $self->assertSame( 'Msg', $msg->getMessage() );
                $self->assertSame( '123', $msg->getCode() );
            }));
        return $inner;
    }

    public function testDispatch ()
    {
        $message = new \r8\Log\Message('Msg', 'Error', 123);

        $log = new \r8\Log( $this->getLogNode('Error') );
        $this->assertSame( $log, $log->dispatch($message) );
    }

    public function testEmergency ()
    {
        $log = new \r8\Log( $this->getLogNode('Emergency') );
        $this->assertSame( $log, $log->emergency('Msg', 123) );
    }

    public function testAlert ()
    {
        $log = new \r8\Log( $this->getLogNode('Alert') );
        $this->assertSame( $log, $log->alert('Msg', 123) );
    }

    public function testCritical ()
    {
        $log = new \r8\Log( $this->getLogNode('Critical') );
        $this->assertSame( $log, $log->critical('Msg', 123) );
    }

    public function testError ()
    {
        $log = new \r8\Log( $this->getLogNode('Error') );
        $this->assertSame( $log, $log->error('Msg', 123) );
    }

    public function testWarning ()
    {
        $log = new \r8\Log( $this->getLogNode('Warning') );
        $this->assertSame( $log, $log->warning('Msg', 123) );
    }

    public function testInfo ()
    {
        $log = new \r8\Log( $this->getLogNode('Information') );
        $this->assertSame( $log, $log->info('Msg', 123) );
    }

    public function testDebug ()
    {
        $log = new \r8\Log( $this->getLogNode('Debug') );
        $this->assertSame( $log, $log->debug('Msg', 123) );
    }

}

?>