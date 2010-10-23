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
class classes_DB_Mysqli_Result extends PHPUnit_Framework_TestCase
{

    /**
     * The MySQLi connection at the disposal of the unit test
     *
     * @var MySQLi
     */
    private $db;

    /**
     * Ensures that all the prerequisites exist for connecting via mysqli
     */
    public function setUp ()
    {
        if ( !extension_loaded("mysqli") )
            $this->markTestSkipped("MySQLi extension is not loaded");

        // Ensure the proper configuration exists
        $config = new \r8\Test\Config(
                "MYSQLI",
                array( "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD", "TABLE" )
            );
        $config->test();

        // Test the connection
        $mysqli = @new mysqli(
                MYSQLI_HOST,
                MYSQLI_USERNAME,
                MYSQLI_PASSWORD,
                MYSQLI_DATABASE,
                MYSQLI_PORT
            );

        if ($mysqli->connect_error)
            $this->markTestSkipped("MySQLi Connection Error: ".  mysqli_connect_error());


        $result = $mysqli->query("DROP TEMPORARY TABLE IF EXISTS `". MYSQLI_TABLE ."`");

        if ( !$result )
            $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);


        $result = $mysqli->query("CREATE TEMPORARY TABLE `". MYSQLI_TABLE ."` (
                              `id` INT NOT NULL auto_increment ,
                           `label` VARCHAR( 255 ) NOT NULL ,
                            `data` VARCHAR( 255 ) NOT NULL ,
                       PRIMARY KEY ( `id` ) ,
                             INDEX ( `label` ))");

        if ( !$result )
            $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);


        $result = $mysqli->query("INSERT INTO `". MYSQLI_TABLE ."`
                                  VALUES (1, 'alpha', 'one'),
                                         (2, 'beta', 'two'),
                                         (3, 'gamma', 'three')");

        if ( !$result )
            $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);

        $this->db = $mysqli;
    }

    public function tearDown ()
    {
        if ( $this->db )
            $this->db->close();
    }

    /**
     * Returns a mock MySQLi result object
     *
     * @return MySQLi_Result
     */
    public function getTestResult ()
    {
        return new \r8\DB\MySQLi\Result(
            $this->db->query("SELECT * FROM ". MYSQLI_TABLE)
        );
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

