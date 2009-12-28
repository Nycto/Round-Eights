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
class classes_Finder_Terminus extends PHPUnit_Framework_TestCase
{

    public function testAddSubDir ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');

        $subdir = new \r8\Finder\SubDir( $wrapped );
        $this->assertSame( array(), $subdir->getSubDirs() );

        $this->assertSame( $subdir, $subdir->addSubDir("subdir") );
        $this->assertSame( array("subdir"), $subdir->getSubDirs() );

        $this->assertSame( $subdir, $subdir->addSubDir("/dir/path/") );
        $this->assertSame( array("subdir", "dir/path"), $subdir->getSubDirs() );
    }

    public function testConstruct ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $subdir = new \r8\Finder\SubDir( $wrapped, "subdir", "/dir/path/" );
        $this->assertSame( array("subdir", "dir/path"), $subdir->getSubDirs() );
    }

}

?>