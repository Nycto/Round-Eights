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
class classes_CLI_Result extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test result object filled with data
     *
     * @return \r8\CLI\Result
     */
    public function getTestResult ()
    {
        return r8( new \r8\CLI\Result )
            ->addOption( array('a', 'b'), array( 'arg1', 'arg2' ) )
            ->addOption( array('x', 'LONG'), array() );
    }

    public function testFlagExists ()
    {
        $result = $this->getTestResult();

        $this->assertTrue( $result->flagExists('a') );
        $this->assertTrue( $result->flagExists('A') );
        $this->assertTrue( $result->flagExists('LONG') );

        $this->assertFalse( $result->flagExists('z') );
        $this->assertFalse( $result->flagExists('Longer') );
    }

    public function testGetArgs ()
    {
        $result = $this->getTestResult();

        $this->assertNull( $result->getArgs('not a flag') );

        $this->assertSame( array(), $result->getArgs('x') );
        $this->assertSame( array('arg1', 'arg2'), $result->getArgs('A') );
    }

}

?>