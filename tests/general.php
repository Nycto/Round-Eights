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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once rtrim( __DIR__, "/" ) ."/../src/commonPHP.php";

error_reporting( E_ALL | E_STRICT );

/**
 * Base unit testing suite class
 *
 * Provides an interface to search and load test suites in a directory
 */
class cPHP_Base_TestSuite extends PHPUnit_Framework_TestSuite
{

    /**
     * Recursively collects a list of test files relative to the given base directory
     *
     * @param String $base The base directory to search in
     * @param String $dir A subdirectory of the base to search in
     * @return array
     */
    private function collectFiles ( $base, $dir = FALSE )
    {

        $base = rtrim($base, "/" ) ."/";

        if ( $dir ) {
            $dir = trim($dir, "/") ."/";
            $search = $base . $dir;
        }
        else {
            $search = $base;
        }

        $result = array();

        $list = scandir( $search );

        foreach ( $list AS $file ) {

            if ( substr($file, 0, 1) == "." )
                continue;

            if ( is_dir( $search . $file ) )
                $result = array_merge( $result, $this->collectFiles( $base, $dir . $file ) );

            else if ( preg_match('/.+\.php$/i', $file) )
                $result[] = $dir . $file;

        }

        sort( $result );

        return $result;
    }

    /**
     * Searches a given directory for PHP files and adds the contained tests to the current suite
     *
     * @param String $testPrefix
     * @param String $dir The directory to search in
     * @param String $exclude The file name to exclude from the search
     * @return object Returns a self reference
     */
    public function addFromFiles ( $testPrefix, $dir, $exclude )
    {

        $dir = rtrim($dir, "/" ) ."/";

        $list = $this->collectFiles($dir);

        foreach ( $list AS $file ) {

            if ( $file == $exclude )
                continue;

            require_once $dir . $file;

            $file = str_replace( ".php", "", $file );
            $file = str_replace( "/", "_", $file );

            $this->addTestSuite( $testPrefix . $file );
        }

        return $this;
    }

}

/**
 * Base test suite for a MySQL connection test
 */
abstract class PHPUnit_MySQLi_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Ensures that all the prerequisites exist for connecting via mysqli
     */
    public function setUp ()
    {
        if ( !extension_loaded("mysqli") )
            $this->markTestSkipped("MySQLi extension is not loaded");

        $config = rtrim( __DIR__, "/") ."/config.php";

        if ( !file_exists($config) )
            $this->markTestSkipped("Config file does not exist: $config");

        if ( !is_readable($config) )
            $this->markTestSkipped("Config file is not readable: $config");

        require_once $config;

        $required = array(
                "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD", "TABLE"
            );

        foreach ( $required AS $constant ) {

            if ( !defined("MYSQLI_". $constant) )
                $this->markTestSkipped("Required constant is not defined: MYSQLI_". $constant);

            $value = constant("MYSQLI_". $constant);

            if ( empty($value) )
                $this->markTestSkipped("Required constant must not be empty: MYSQLI_". $constant);
        }

        // Test the connection
        $mysqli = new mysqli(
                MYSQLI_HOST,
                MYSQLI_USERNAME,
                MYSQLI_PASSWORD,
                MYSQLI_DATABASE,
                MYSQLI_PORT
            );

        if ($mysqli->connect_error)
            $this->markTestSkipped("MySQLi Connection Error: ".  mysqli_connect_error());

    }

    /**
     * Returns the data in the config.php file as a URI
     */
    public function getURI ()
    {
        return "db://"
            .MYSQLI_USERNAME .":". MYSQLI_PASSWORD ."@"
            .MYSQLI_HOST .":". MYSQLI_PORT
            ."/". MYSQLI_DATABASE;
    }

    /**
     * Returns and maintains a \cPHP\DB\MySQLi Link
     *
     * This will also create a table and fill it with data, according to the
     * settings in the config.php file
     */
    public function getLink ()
    {
        static $link;

        if ( !isset($link) || !$link->isConnected() ) {
            $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );


            $mysqli = $link->getLink();


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

        }


        $mysqli = $link->getLink();


            $result = $mysqli->query("TRUNCATE TABLE `". MYSQLI_TABLE ."`");

            if ( !$result )
                $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);

        $result = $mysqli->query("INSERT INTO `". MYSQLI_TABLE ."`
                             VALUES (1, 'alpha', 'one'),
                                    (2, 'beta', 'two'),
                                    (3, 'gamma', 'three')");

        if ( !$result )
            $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);


        return $link;
    }

}

/**
 * Base test class for tests that require an empty temporary file
 */
abstract class PHPUnit_EmptyFile_Framework_TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * This is a list of all the files created with getTempFileName. They will
     * automatically be removed on teardown
     */
    private $cleanup = array();

    /**
     * The name of the temporary file
     */
    protected $file;

    /**
     * Returns the name of a temporary file
     *
     * This does not create the file, it mearly returns a unique, temporary path
     *
     * @return string
     */
    public function getTempFileName ()
    {
        $result = rtrim( sys_get_temp_dir(), "/" ) ."/cPHP_unitTest_". uniqid();
        $this->cleanup[] = $result;
        return $result;
    }

    /**
     * Setup creates the file
     */
    public function setUp ()
    {
        $this->file = $this->getTempFileName();

        if ( !@touch( $this->file ) )
            $this->markTestSkipped("Unable to create temporary file");
    }

    /**
     * Teardown will automatically remove the file
     */
    public function tearDown ()
    {
        foreach ( $this->cleanup AS $file ) {

            if ( file_exists($file) ) {

                // Fix the permissions so we can delete it
                if ( !is_writable($file) )
                    @chmod($file, 0600);

                @unlink( $file );

            }
        }
    }

}

/**
 * Base test class for tests that require a temporary file that has content
 */
abstract class PHPUnit_TestFile_Framework_TestCase extends PHPUnit_EmptyFile_Framework_TestCase
{

    /**
     * Setup creates the file
     */
    public function setUp ()
    {
        parent::setUp();

        $wrote = file_put_contents(
                $this->file,
                "This is a string\nof data that is put\nin the test file"
            );

        if ( $wrote == 0 ) {
            $this->markTestSkipped("Unable to write data to test file");
            @unlink( $this->file );
        }

    }

}

/**
 * Stub of the Env class that allows tests to create an instance that represents
 * a specific environment
 */
class Stub_Env extends \cPHP\Env
{

    static public function fromArray( array $data )
    {
        return new static( $data );
    }

}

?>