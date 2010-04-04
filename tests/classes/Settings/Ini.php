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
class classes_Settings_Ini
{

    public static function suite()
    {
        $suite = new r8_Base_TestSuite;
        $suite->addTestSuite( 'classes_Settings_Ini_NoFile' );
        $suite->addTestSuite( 'classes_Settings_Ini_WithFile' );
        return $suite;
    }

}

class classes_Settings_Ini_NoFile extends PHPUnit_Framework_TestCase
{

    public function testNoFile ()
    {
        $settings = new \r8\Settings\Ini( "Not a real file" );

        try {
            $settings->get('stuff', 'one');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Filesystem\Missing $err ) {}
    }

}

class classes_Settings_Ini_WithFile extends PHPUnit_EmptyFile_Framework_TestCase
{

    /**
     * Setup creates the file
     */
    public function setUp ()
    {
        parent::setUp();

        $wrote = file_put_contents(
            $this->file,
            "[stuff]\n"
            ."one=first\n"
            ."two=second\n"
            ."[trogdor]\n"
            ."burninate=1\n"
            ."killable=0\n"
        );

        if ( $wrote == 0 ) {
            $this->markTestSkipped("Unable to write data to test file");
            @unlink( $this->file );
        }
    }

    public function testUnreadable ()
    {
        chmod( $this->file, 0000 );

        $settings = new \r8\Settings\Ini( $this->file );

        try {
            $settings->get('stuff', 'one');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Filesystem\Permissions $err ) {}
    }

    public function testGet ()
    {
        $settings = new \r8\Settings\Ini( $this->file );

        $this->assertSame( "first", $settings->get('stuff', 'one') );
        $this->assertSame( "1", $settings->get('trogdor', 'burninate') );
        $this->assertNull( $settings->get('Group', 'Key')  );
        $this->assertNull( $settings->get('STUFF', 'ONE')  );
    }

    public function testExists ()
    {
        $settings = new \r8\Settings\Ini( $this->file );

        $this->assertTrue( $settings->exists('stuff', 'one') );
        $this->assertTrue( $settings->exists('trogdor', 'burninate') );
        $this->assertFalse( $settings->exists('Group', 'Key')  );
        $this->assertFalse( $settings->exists('STUFF', 'ONE')  );
    }

    public function testGetGroup ()
    {
        $settings = new \r8\Settings\Ini( $this->file );

        $this->assertSame(
            array( 'one' => 'first', 'two' => 'second' ),
            $settings->getGroup('stuff')
        );
        $this->assertSame(
            array( 'burninate' => '1', 'killable' => '0' ),
            $settings->getGroup('trogdor')
        );

        $this->assertSame( array(), $settings->getGroup('things') );
    }

    public function testSerialize ()
    {
        $settings = new \r8\Settings\Ini( $this->file );

        $unserialized = unserialize( serialize($settings) );
        $this->assertEquals( $settings, $unserialized );
    }

}

?>