<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once rtrim( __DIR__, "/" ) ."/../src/RaindropPHP.php";

error_reporting( E_ALL | E_STRICT );

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Includes the config file and ensures that a set of constants exists
 */
class h2o_Test_Config
{

    /**
     * The prefix for the constants
     *
     * @param String
     */
    private $prefix;

    /**
     * The list of constants
     *
     * @var array
     */
    private $constants;

    /**
     * Constructor...
     *
     * @param String $prefix The prefix for all the constants
     * @param array $constants The list of constants
     */
    public function __construct( $prefix, array $constants )
    {
        $this->prefix = trim( strval($prefix) );
        $this->constants = $constants;
    }

    /**
     * Helper function for throwing the skip exception
     *
     * @throws PHPUnit_Framework_SkippedTestError
     * @param String $message The message to skip with
     */
    private function skip ( $message )
    {
        throw new PHPUnit_Framework_SkippedTestError($message);
    }

    /**
     * Tests to ensure the config file exists and that all the required
     * constants are defined
     *
     * @throws PHPUnit_Framework_SkippedTestError This will be thrown if any
     *      of the constants dont exist
     * @return null
     */
    public function test ()
    {
        $config = rtrim( __DIR__, "/") ."/config.php";

        if ( !file_exists($config) )
            $this->skip("Config file does not exist: $config");

        if ( !is_readable($config) )
            $this->skip("Config file is not readable: $config");

        require_once $config;

        foreach ( $this->constants AS $constant ) {

            $constant = $this->prefix ."_". trim( strval($constant) );

            if ( !defined($constant) )
                $this->skip("Required constant is not defined: ". $constant);

            $value = constant($constant);

            if ( empty($value) )
                $this->skip("Required constant must not be empty: ". $constant);
        }
    }

}

/**
 * Base unit testing suite class
 *
 * Provides an interface to search and load test suites in a directory
 */
class h2o_Base_TestSuite extends PHPUnit_Framework_TestSuite
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

        // Ensure the proper configuration exists
        $config = new h2o_Test_Config(
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
     * Returns and maintains a \h2o\DB\MySQLi Link
     *
     * This will also create a table and fill it with data, according to the
     * settings in the config.php file
     */
    public function getLink ()
    {
        static $link;

        if ( !isset($link) || !$link->isConnected() ) {

            $link = new \h2o\DB\MySQLi\Link( $this->getURI() );

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
        $result = rtrim( sys_get_temp_dir(), "/" ) ."/h2o_unitTest_". uniqid();
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
 * Base test class for tests that use temporary files/directories
 */
abstract class PHPUnit_Dir_Framework_TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * The temporary directory that was created
     */
    protected $dir;

    /**
     * Creates a new temporary directory with a set of fake files in it
     */
    public function setUp ()
    {
        $this->dir = rtrim( sys_get_temp_dir(), "/" ) ."/h2o_". uniqid();

        if (!mkdir( $this->dir ))
            $this->markTestSkipped("Unable to create temporary directory: ". $this->dir);

        $toCreate = array(
                "first/.",
                "second/second-one",
                "third/third-one",
                "third/third-two",
                "third/third-three",
                "third/fourth/.",
                "third/fourth/fourth-one",
                "third/fourth/fourth-two",
                "one",
                "two",
                "three",
                "four",
            );

        foreach ( $toCreate AS $path ) {

            $dirname = dirname($path);

            if ( $dirname != "." ) {

                $dirname = $this->dir ."/". $dirname;

                if ( !is_dir($dirname) && !mkdir($dirname, 0777) )
                    $this->markTestSkipped("Unable to create temporary dir: ". $dirname );

            }

            if ( basename($path) != "." ) {

                $basename = $this->dir ."/". $path;

                if ( !touch($basename) )
                    $this->markTestSkipped("Unable to create temporary file: ". $basename );

                @chmod( $basename, 0777 );
            }

        }

    }

    /**
     * Deletes a given path and everything in it
     */
    private function delete ( $path )
    {

        if ( is_file($path) ) {
            @chmod($path, 0777);
            @unlink($path);
        }

        else if( is_dir($path) ) {

            @chmod($path, 0777);

            foreach( new DirectoryIterator($path) as $item ) {

                if( $item->isDot() )
                    continue;

                if( $item->isFile() )
                    $this->delete( $item->getPathName() );

                else if( $item->isDir() )
                    $this->delete( $item->getRealPath() );

                unset($_res);
            }

            @rmdir( $path );

        }

    }

    /**
     * Deletes the temporary files
     */
    public function tearDown ()
    {
        $this->delete( $this->dir );
    }

}

/**
 * Asserts that the array produced by an iterator are exactly equal to a given value
 */
class PHPUnit_Framework_Constraint_Iterator extends PHPUnit_Framework_Constraint
{

    /**
     * The value the iterator should produce
     *
     * @var array
     */
    private $value;

    /**
     * This is a cache to keep from iterating over the same object multiple times
     *
     * @var array
     */
    private $cache = array();

    /**
     * Asserts that the given value produces the expected result when iterated over
     *
     * @return null
     */
    static public function assert ( array $expected, $actual )
    {
        PHPUnit_Framework_TestCase::assertThat(
                $actual,
                new self( $expected )
            );
    }

    /**
     * Constructor...
     *
     * @param Array $value The value the iterator should produce
     */
    public function __construct( array $value )
    {
        $this->value = $value;
    }

    /**
     * Turns an iterator into an array while preventing too much iteration
     *
     * @return Array
     */
    public function iteratorToArray ( Traversable $iterator )
    {
        $hash = spl_object_hash( $iterator );

        // First check the cache
        if ( isset($this->cache[$hash]) )
            return $this->cache[$hash];

        $max = count( $this->value );

        // Give them a 25% bonus to make debugging easier
        $max *= 1.25;

        $i = 0;

        $result = array();

        foreach ( $iterator AS $key => $value )
        {
            $i++;

            if ( $i > $max )
                break;

            $result[ $key ] = $value;
        }

        $this->cache[$hash] = $result;

        return $result;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns TRUE if the
     * constraint is met, FALSE otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    public function evaluate( $other )
    {
        if ( !($other instanceof Traversable) )
            return FALSE;

        return $this->iteratorToArray($other) === $this->value;
    }

    /**
     * @param   mixed   $other The value passed to evaluate() which failed the
     *                         constraint check.
     * @param   string  $description A string with extra description of what was
     *                               going on while the evaluation failed.
     * @param   boolean $not Flag to indicate negation.
     * @return String
     */
    protected function customFailureDescription($other, $description, $not)
    {
        if ( !($other instanceof Traversable) )
            return PHPUnit_Util_Type::toString($other) ." is an instance of Traversable";

        $diff = new PHPUnit_Framework_ComparisonFailure_Array(
        	        $this->value,
        	        $this->iteratorToArray($other)

            );

        return "Iteration did not produce the expected result:\n"
            .$diff->toString();
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "produces". PHPUnit_Util_Type::toString($this->value) ."when iterated over";
    }

}

?>