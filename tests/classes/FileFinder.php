<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_filefinder extends PHPUnit_Framework_TestCase
{

    public function testFallbackAccessors ()
    {
        $finder = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $this->assertNull( $finder->getFallback() );
        $this->assertFalse( $finder->fallbackExists() );

        $fallback = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $finder, $finder->setFallback($fallback) );
        $this->assertSame( $fallback, $finder->getFallback() );
        $this->assertTrue( $finder->fallbackExists() );

        $fallback2 = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $finder, $finder->setFallback($fallback2) );
        $this->assertSame( $fallback2, $finder->getFallback() );
        $this->assertTrue( $finder->fallbackExists() );

        $this->assertSame( $finder, $finder->clearFallback() );
        $this->assertNull( $finder->getFallback() );
        $this->assertFalse( $finder->fallbackExists() );

        $fallback = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $finder, $finder->setFallback($fallback) );
        $this->assertSame( $fallback, $finder->getFallback() );
        $this->assertTrue( $finder->fallbackExists() );
    }

    public function testFallback_infiniteLoop ()
    {
        $finder = $this->getmock( '\cPHP\FileFinder', array('internalFind') );

        $fallback = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $finder->setFallback( $fallback );

        $fallback2 = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $fallback->setFallback( $fallback2 );

        try {
            $fallback2->setFallback( $finder );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Interaction $err ) {
            $this->assertSame( "Setting Fallback creates an infinite loop", $err->getMessage() );
        }
    }

    public function testGetTopFallback_self ()
    {
        $finder = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $finder, $finder->getTopFallback() );
    }

    public function testGetTopFallback_chain ()
    {
        $finder = $this->getmock( '\cPHP\FileFinder', array('internalFind') );

        $fallback = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $finder->setFallback( $fallback );

        $fallback2 = $this->getmock( '\cPHP\FileFinder', array('internalFind') );
        $fallback->setFallback( $fallback2 );

        $this->assertSame( $fallback2, $finder->getTopFallback() );
        $this->assertSame( $fallback2, $fallback->getTopFallback() );
    }

}

?>