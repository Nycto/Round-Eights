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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_cache_db_mysql extends PHPUnit_Framework_TestCase
{

    /**
     * The mock database link that will be loaded into the test object
     *
     * @var Object
     */
    protected $link;

    /**
     * Returns a new mock cPHP\Cache\DB object
     *
     * @return Object
     */
    public function getTestLink ()
    {
        $link = $this->getMock('cPHP\iface\DB\Link', array('query', 'quote', 'escape'));

        $link->expects( $this->any() )
            ->method('escape')
            ->will( $this->returnCallback(function ($value) {
                return addslashes($value);
            }));

        $link->expects( $this->any() )
            ->method('quote')
            ->will( $this->returnCallback(function ($value) {
                return "'". addslashes($value) ."'";
            }));

        return $link;
    }

    /**
     * Returns a new mock cPHP\Cache\DB object
     *
     * @return Object
     */
    public function getTestObj ()
    {
        $this->link = $this->getTestLink();
        return new \cPHP\Cache\DB\MySQL( $this->link, 'tble', 'key', 'hash', 'expir', 'value' );
    }

    public function testGet ()
    {
        $cache = $this->getTestObj();

        $read = $this->getMock(
                'cPHP\DB\Result\Read',
                array('count', 'rewind', 'current', 'rawCount', 'rawFields',
                        'rawFetch', 'rawSeek', 'rawFree'),
                array(new stdClass, "SELECT Hash, Value FROM table")
            );

        $this->link->expects( $this->once() )
            ->method('query')
            ->with( $this->logicalAnd(
                    $this->stringContains('SELECT `value` AS `Value`,'),
                    $this->stringContains('`hash` AS `Hash`'),
                    $this->stringContains('FROM `tble`'),
                    $this->stringContains('WHERE `expir` >= NOW()'),
                    $this->stringContains("AND `key` = '57d319c1fcd99a3594ae888cba1e496e'"),
                    $this->stringContains("LIMIT 1")
                ) )
            ->will( $this->returnValue( $read ) );

        $read->expects( $this->once() )
            ->method('count')
            ->will( $this->returnValue(1) );

        $read->expects( $this->once() )
            ->method('rewind')
            ->will( $this->returnValue($read) );

        $read->expects( $this->once() )
            ->method('current')
            ->will( $this->returnValue(array(
                    "Value" => serialize(101),
                    "Hash" => md5( serialize(101) )
                )) );

        $this->assertSame(101, $cache->get("A Label"));
    }

}

?>