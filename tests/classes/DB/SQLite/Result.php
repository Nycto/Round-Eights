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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_DB_SQLite_Result extends PHPUnit_Framework_TestCase
{

    /**
     * The SQLite connection at the disposal of the unit test
     *
     * @var SQLite
     */
    private $db;

    /**
     * Ensures that all the prerequisites exist for connecting via mysqli
     */
    public function setUp ()
    {
        if ( !extension_loaded("sqlite") )
            $this->markTestSkipped("SQLite extension is not loaded");

        // Ensure the proper configuration exists
        $config = new r8_Test_Config( "SQLITE", array("FILE", "TABLE") );
        $config->test();

        // Test the connection
        $error = null;
        $db = @sqlite_open( SQLITE_FILE, 0666, $error );
        if ( !$db )
            $this->markTestSkipped("SQLite Connection Error: ". $error);


        $error = null;
        $result = sqlite_query(
            $db,
            "CREATE TEMPORARY TABLE ". SQLITE_TABLE ."(
                id INT NOT NULL PRIMARY KEY,
                label VARCHAR( 255 ) NOT NULL,
                data VARCHAR( 255 ) NOT NULL
            )",
            SQLITE_ASSOC, $error
        );
        if ( !$result )
            $this->markTestSkipped("SQLite Error: ". $error);


        $error = null;
        $result = sqlite_query(
            $db, "INSERT INTO ". SQLITE_TABLE ." VALUES (1, 'alpha', 'one')",
            SQLITE_ASSOC, $error
        );
        if ( !$result )
            $this->markTestSkipped("SQLite Error: ". $error);


        $error = null;
        $result = sqlite_query(
            $db, "INSERT INTO ". SQLITE_TABLE ." VALUES (2, 'beta', 'two')",
            SQLITE_ASSOC, $error
        );
        if ( !$result )
            $this->markTestSkipped("SQLite Error: ". $error);


        $error = null;
        $result = sqlite_query(
            $db, "INSERT INTO ". SQLITE_TABLE ." VALUES (3, 'gamma', 'three')",
            SQLITE_ASSOC, $error
        );
        if ( !$result )
            $this->markTestSkipped("SQLite Error: ". $error);


        $this->db = $db;
    }

    public function tearDown ()
    {
        if ( $this->db )
            sqlite_close( $this->db );
    }

    /**
     * Returns a mock Database query result object
     *
     * @return \r8\DB\SQLite\Result
     */
    public function getTestResult ()
    {
        $error = null;
        $result = sqlite_query(
            $this->db, "SELECT * FROM ". SQLITE_TABLE,
            SQLITE_ASSOC, $error
        );
        if ( !$result )
            $this->markTestSkipped("SQLite Error: ". $error);

        return new \r8\DB\SQLite\Result( $result );
    }

    public function testConstruct_Errors ()
    {
        try {
            new \r8\DB\SQLite\Result( "Not a resource" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a SQLite Result resource", $err->getMessage() );
        }

        try {
            new \r8\DB\SQLite\Result( STDIN );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a SQLite Result resource", $err->getMessage() );
        }
    }

    public function testCount ()
    {
        $result = $this->getTestResult();
        $this->assertSame( 3, $result->count() );
    }

    public function testFields ()
    {
        $result = $this->getTestResult();

        $this->assertSame(
                array('id', 'label', 'data'),
                $result->getFields()
            );
    }

    public function testFetch ()
    {
        $result = $this->getTestResult();

        $this->assertSame(
                array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                $result->fetch()
            );

        $this->assertSame(
                array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                $result->fetch()
            );

        $this->assertSame(
                array('id' => '3', 'label' => 'gamma', 'data' => 'three'),
                $result->fetch()
            );

        $this->assertNull( $result->fetch() );
    }

    public function testSeek ()
    {
        $result = $this->getTestResult();

        $this->assertSame(
                array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                $result->fetch()
            );

        $this->assertSame(
                array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                $result->fetch()
            );

        $this->assertNull( $result->seek(0) );

        $this->assertSame(
                array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                $result->fetch()
            );

        $this->assertNull( $result->seek(2) );

        $this->assertSame(
                array('id' => '3', 'label' => 'gamma', 'data' => 'three'),
                $result->fetch()
            );
    }

    public function testFree ()
    {
        $result = $this->getTestResult();
        $this->assertNull( $result->free() );
    }

}

?>