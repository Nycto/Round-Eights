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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_mail_transport extends PHPUnit_Framework_TestCase
{

    public function testSend_incomplete ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));
        $mail = new \r8\Mail( $transport );

        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( '"From" Address must be set to send an email', $err->getMessage() );
        }

        $mail->setFrom('test@example.com');

        try {
            $transport->send( $mail );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( '"To" Address must be set to send an email', $err->getMessage() );
        }
    }

    public function testSend_valid ()
    {
        $transport = $this->getMock('r8\Mail\Transport', array('internalSend'));

        $mail = new \r8\Mail( $transport );
        $mail->setFrom('test@example.com');
        $mail->addTo('destination@example.com');

        $transport->expects( $this->once() )
            ->method( 'internalSend' )
            ->with( $this->equalTo($mail) );

        $this->assertSame( $transport, $transport->send($mail) );
    }

}

?>