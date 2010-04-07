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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

define("r8_SUPPRESS_HANDLERS", TRUE);
define("r8_TESTCONFIG", rtrim( __DIR__, "/" ) .'/config.php');

require_once rtrim( __DIR__, "/" ) ."/../src/RoundEights.php";

error_reporting( E_ALL | E_STRICT );

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * Base test class for tests that require a temporary file that has content
 */
abstract class PHPUnit_TestFile_Framework_TestCase extends \r8\Test\TestCase\EmptyFile
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
        $this->dir = rtrim( sys_get_temp_dir(), "/" ) ."/r8_". uniqid();

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
 * Asserts that two SQL queries are equivalent
 */
class PHPUnit_Framework_Constraint_SQL extends PHPUnit_Framework_Constraint
{

    /**
     * Any keywords to add a break behind
     *
     * @var array
     */
    static private $breaks = array(
        'SELECT', 'AS', 'FROM', 'INNER', 'OUTER', 'LEFT', 'STRAIGHT_JOIN', 'NATURAL',
        'ON', 'USING', 'USE', 'IGNORE', 'FORCE', 'WHERE', 'OR', 'AND', 'GROUP BY',
        'HAVING', 'ORDER BY', 'LIMIT', 'PROCEDURE', 'INTO', 'FOR UPDATE', 'UNION',
        'INSERT', 'VALUES', 'UPDATE', 'SET', 'DELETE', 'REPLACE', 'SET'
    );

    /**
     * The list of reserved SQL keywords
     *
     * @var Array
     */
    static private $keywords = array(
        'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 'BEFORE',
        'BETWEEN', 'BIGINT', 'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE', 'CASE',
        'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN','CONDITION',
        'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_DATE',
        'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE',
        'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND', 'DAY_MINUTE', 'DAY_SECOND',
        'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT', 'DELAYED', 'DELETE', 'DESC',
        'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE',
        'DROP', 'DUAL', 'EACH', 'ELSE', 'ELSEIF', 'ENCLOSED', 'ESCAPED', 'EXISTS',
        'EXIT', 'EXPLAIN', 'FALSE', 'FETCH', 'FLOAT', 'FLOAT4', 'FLOAT8', 'FOR',
        'FORCE', 'FOREIGN', 'FROM', 'FULLTEXT', 'GRANT', 'GROUP', 'HAVING',
        'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND', 'IF',
        'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 'INOUT', 'INSENSITIVE', 'INSERT',
        'INT', 'INT1', 'INT2', 'INT3', 'INT4', 'INT8', 'INTEGER', 'INTERVAL', 'INTO',
        'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LEADING', 'LEAVE', 'LEFT',
        'LIKE', 'LIMIT', 'LINES', 'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK',
        'LONG', 'LONGBLOB', 'LONGTEXT', 'LOOP', 'LOW_PRIORITY', 'MATCH', 'MEDIUMBLOB',
        'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND',
        'MOD', 'MODIFIES', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL', 'NUMERIC',
        'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUT', 'OUTER',
        'OUTFILE', 'PRECISION', 'PRIMARY', 'PROCEDURE', 'PURGE', 'READ', 'READS',
        'REAL', 'REFERENCES', 'REGEXP', 'RELEASE', 'RENAME', 'REPEAT', 'REPLACE',
        'REQUIRE', 'RESTRICT', 'RETURN', 'REVOKE', 'RIGHT', 'RLIKE', 'SCHEMA',
        'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE', 'SEPARATOR',
        'SET', 'SHOW', 'SMALLINT', 'SONAME', 'SPATIAL', 'SPECIFIC', 'SQL',
        'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT', 'SQL_CALC_FOUND_ROWS',
        'SQL_SMALL_RESULT', 'SSL', 'STARTING', 'STRAIGHT_JOIN', 'TABLE', 'TERMINATED',
        'THEN', 'TINYBLOB', 'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRIGGER',
        'TRUE', 'UNDO', 'UNION', 'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE',
        'USE', 'USING', 'UTC_DATE', 'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES', 'VARBINARY',
        'VARCHAR', 'VARCHARACTER', 'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH',
        'WRITE', 'XOR', 'YEAR_MONTH', 'ZEROFILL'
    );

    /**
     * The SQL query to compare against
     *
     * @var String
     */
    private $sql;

    /**
     * Asserts that the given value produces the expected result when iterated over
     *
     * @return null
     */
    static public function assert ( $expected, $actual )
    {
        PHPUnit_Framework_TestCase::assertThat(
                $actual,
                new self( $expected )
            );
    }

    /**
     * Cleans up a SQL query for comparison
     *
     * @param String $sql The SQL to clean
     * @return String
     */
    static public function cleanSQL ( $sql )
    {
        $quoter = new \r8\Quoter;
        $quoter->setQuote('"')->setQuote("'")->setQuote("`");

        $parsed = $quoter->parse( $sql );
        $parsed->setIncludeQuoted( FALSE )->setIncludeUnquoted( TRUE );

        $keywords = '/\b(?:'. implode("|", self::$keywords) .')\b/i';
        $breaks = '/\b('. implode("|", self::$breaks) .')\b/i';

        $parsed->filter(new \r8\Filter\Chain(
            r8(new \r8\Curry\Call('str_replace'))->setLeft( array("\n", "\r"), " " ),
            r8(new \r8\Curry\Call('\r8\str\stripRepeats'))->setRight(" "),
            r8(new \r8\Curry\Call('preg_replace_callback'))
                ->setLeft( $keywords, function ( $value ) {
                    return strtoupper( $value[0] );
                } ),
            r8(new \r8\Curry\Call('preg_replace'))
                ->setLeft( $breaks, "\n\\1" )
        ));

        return trim( $parsed->__toString(), " ;");
    }

    /**
     * Constructor...
     *
     * @param String $sql The SQL query to compare against
     */
    public function __construct( $sql )
    {
        $this->sql = self::cleanSQL( $sql );
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
        return self::cleanSQL($other) === $this->sql;
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
        $diff = new PHPUnit_Framework_ComparisonFailure_String(
            $this->sql,
            self::cleanSQL( $other )
        );

        return "SQL queries are not equivalent:\n"
            .$diff->toString();
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "is equivalent to the SQL query ". PHPUnit_Util_Type::toString($this->sql);
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
     * Converts an interator to an array while providing a maximum result cap
     *
     * @param Integer $max The maximum number of results
     * @param Traversable $iterator The iterator to convert
     * @param Boolean $recurse Whether to recursively reduce inner iterators
     * @return Array
     */
    static public function iteratorToArray ( $max, \Traversable $iterator, $recurse = FALSE )
    {
        $i = 0;

        $result = array();

        foreach ( $iterator AS $key => $value )
        {
            if ( $recurse && $value instanceof \Traversable )
                $value = self::iteratorToArray( $max - 1, $value, TRUE );

            $result[ $key ] = $value;

            $i++;
            if ( $i > $max )
                break;
        }

        return $result;
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
    public function toArray ( Traversable $iterator )
    {
        $hash = spl_object_hash( $iterator );

        // First check the cache
        if ( isset($this->cache[$hash]) )
            return $this->cache[$hash];

        $max = count( $this->value );

        // Give them a 50% bonus to make debugging easier
        $max = floor( $max * 1.50 );

        $this->cache[$hash] = self::iteratorToArray( $max, $iterator );

        return $this->cache[$hash];
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

        return $this->toArray($other) === $this->value;
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

        $other = $this->toArray($other);

        $diff = new PHPUnit_Framework_ComparisonFailure_Array(
            $this->value,
            $other
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