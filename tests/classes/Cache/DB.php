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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_cache_db extends PHPUnit_Framework_TestCase
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

        return $this->getMock(
                'cPHP\Cache\DB',
                array('internalGet', 'getForUpdate', 'set', 'add', 'replace', 'append',
                        'prepend', 'increment', 'decrement', 'delete', 'flush'),
                array( $this->link, 'tble', 'key', 'hash', 'expir', 'value' )
            );
    }

    public function testLinkAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( $this->link, $cache->getLink() );

        $link = $this->getTestLink();
        $this->assertSame( $cache, $cache->setLink($link) );
        $this->assertSame( $link, $cache->getLink() );
    }

    public function testTableAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( 'tble', $cache->getTable() );

        $this->assertSame( $cache, $cache->setTable('  Table_Name  ') );
        $this->assertSame( 'Table_Name', $cache->getTable() );

        try {
            $cache->setTable('  ');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame('Must not be empty', $err->getMessage());
        }
    }

    public function testKeyAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( 'key', $cache->getKey() );

        $this->assertSame( $cache, $cache->setKey('Key_name') );
        $this->assertSame( 'Key_name', $cache->getKey() );

        try {
            $cache->setKey('  ');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame('Must not be empty', $err->getMessage());
        }
    }

    public function testHashAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( 'hash', $cache->getHash() );

        $this->assertSame( $cache, $cache->setHash('Hash_name') );
        $this->assertSame( 'Hash_name', $cache->getHash() );

        try {
            $cache->setHash('  ');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame('Must not be empty', $err->getMessage());
        }
    }

    public function testExpirationAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( 'expir', $cache->getExpiration() );

        $this->assertSame( $cache, $cache->setExpiration('Expiration_name') );
        $this->assertSame( 'Expiration_name', $cache->getExpiration() );

        try {
            $cache->setExpiration('  ');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame('Must not be empty', $err->getMessage());
        }
    }

    public function testValueAccessors ()
    {
        $cache = $this->getTestObj();

        $this->assertSame( 'value', $cache->getValue() );

        $this->assertSame( $cache, $cache->setValue('Value_name') );
        $this->assertSame( 'Value_name', $cache->getValue() );

        try {
            $cache->setValue('  ');
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame('Must not be empty', $err->getMessage());
        }
    }

    public function testNormalizeString ()
    {
        $cache = $this->getTestObj();

        $this->assertSame(
                "bdba1e73537ab471b9fcc506e827fb10",
                $cache->normalizeKey("Key Value")
            );

        $this->assertSame(
                "d41d8cd98f00b204e9800998ecf8427e",
                $cache->normalizeKey( new stdClass )
            );
    }

    public function testGet ()
    {
        $cache = $this->getTestObj();

        $cache->expects( $this->once() )
            ->method('internalGet')
            ->with( $this->equalTo('94a8446abb76477df9ce1bd5d7dce5f8') )
            ->will( $this->returnValue("SELECT Hash, Value FROM table") );

        $read = $this->getMock(
                'cPHP\DB\Result\Read',
                array('count', 'rewind', 'current', 'rawCount', 'rawFields',
                        'rawFetch', 'rawSeek', 'rawFree'),
                array(new stdClass, "SELECT Hash, Value FROM table")
            );

        $this->link->expects( $this->once() )
            ->method('query')
            ->with( $this->equalTo('SELECT Hash, Value FROM table') )
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
                    "Value" => 's:13:"Chunk of data";',
                    "Hash" => '5c75fc8da8565c7dbabf500c40c024d2'
                )) );

        $this->assertSame("Chunk of data", $cache->get("LookupKey"));
    }

    public function testGet_notSet ()
    {
        $cache = $this->getTestObj();

        $cache->expects( $this->once() )
            ->method('internalGet')
            ->with( $this->equalTo('94a8446abb76477df9ce1bd5d7dce5f8') )
            ->will( $this->returnValue("SELECT Hash, Value FROM table") );

        $read = $this->getMock(
                'cPHP\DB\Result\Read',
                array('count', 'rewind', 'current', 'rawCount', 'rawFields',
                        'rawFetch', 'rawSeek', 'rawFree'),
                array(new stdClass, "SELECT Hash, Value FROM table")
            );

        $this->link->expects( $this->once() )
            ->method('query')
            ->with( $this->equalTo('SELECT Hash, Value FROM table') )
            ->will( $this->returnValue( $read ) );

        $read->expects( $this->once() )
            ->method('count')
            ->will( $this->returnValue(0) );

        $this->assertNull($cache->get("LookupKey"));
    }

}

?>