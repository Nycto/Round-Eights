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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_template_file extends PHPUnit_Framework_TestCase
{

    public function getMockTpl ()
    {
        return $this->getMock(
                'cPHP\Template\File',
                array('display')
            );
    }

    public function testFinderAccessors ()
    {
        $tpl = $this->getMockTpl();
        $this->assertNull( $tpl->getFinder() );
        $this->assertFalse( $tpl->finderExists() );

        $finder = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $tpl, $tpl->setFinder( $finder ) );
        $this->assertSame( $finder, $tpl->getFinder() );
        $this->assertTrue( $tpl->finderExists() );

        $finder2 = $this->getMock( 'cPHP\FileFinder', array('internalFind') );
        $this->assertSame( $tpl, $tpl->setFinder( $finder2 ) );
        $this->assertSame( $finder2, $tpl->getFinder() );
        $this->assertTrue( $tpl->finderExists() );

        $this->assertSame( $tpl, $tpl->clearFinder() );
        $this->assertNull( $tpl->getFinder() );
        $this->assertFalse( $tpl->finderExists() );
    }

}

?>