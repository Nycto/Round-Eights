<?php
/**
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
 * @package PHPUnit
 */

namespace r8\Test\Constraint;

/**
 * Asserts that two SQL queries are equivalent
 */
class SQL extends \PHPUnit_Framework_Constraint
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
        \PHPUnit_Framework_TestCase::assertThat(
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
        $diff = new \PHPUnit_Framework_ComparisonFailure_String(
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
        return "is equivalent to the SQL query ". \PHPUnit_Util_Type::toString($this->sql);
    }

}

?>