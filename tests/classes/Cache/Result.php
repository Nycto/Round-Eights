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
class classes_cache_result extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a new mock cPHP\Cache\DB object
     *
     * @return Object
     */
    public function getTestCache ()
    {
        return $this->getMock(
                'cPHP\iface\Cache',
                array('get', 'getForUpdate', 'set', 'add', 'replace', 'append',
                        'prepend', 'increment', 'decrement', 'delete', 'flush')
            );
    }

    public function testAccessors ()
    {
        $cache = $this->getTestCache();

        $result = new \cPHP\Cache\Result( $cache, "KeyValue", "ABC123", "Chunk of data");

        $this->assertSame( $cache, $result->getCache() );
        $this->assertSame( "KeyValue", $result->getKey() );
        $this->assertSame( "ABC123", $result->getHash() );
        $this->assertSame( "Chunk of data", $result->getValue() );
    }

    public function testAccessors_objects ()
    {
        $cache = $this->getTestCache();
        $hash = new stdClass;
        $value = new stdClass;

        $result = new \cPHP\Cache\Result( $cache, 1234, $hash, $value);

        $this->assertSame( $cache, $result->getCache() );
        $this->assertSame( "1234", $result->getKey() );
        $this->assertSame( $hash, $result->getHash() );
        $this->assertSame( $value, $result->getValue() );
    }

}

?>