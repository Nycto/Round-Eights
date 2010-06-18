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
 * Unit Tests
 */
class classes_CLIArgs_Collection extends PHPUnit_Framework_TestCase
{

    public function testFindByFlag ()
    {
        $opt1 = new \r8\CLIArgs\Option('a', 'blah');
        $opt2 = new \r8\CLIArgs\Option('b', 'hork');
        $opt3 = new \r8\CLIArgs\Option('b', 'another');

        $collection = new \r8\CLIArgs\Collection;
        $collection->addOption( $opt1 )
            ->addOption( $opt2 )
            ->addOption( $opt3 );

        $this->assertSame( $opt1, $collection->findByFlag('a') );
        $this->assertSame( $opt2, $collection->findByFlag('b') );
        $this->assertNull( $collection->findbyFlag('switch') );
    }

}

?>