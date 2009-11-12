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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Session_Cookie extends PHPUnit_Framework_TestCase
{

    /**
     * A copy of the cookie data
     *
     * @var array
     */
    private $cookie;

    /**
     * Sets up the test scenario
     *
     * @return Null
     */
    public function setUp ()
    {
        $this->cookie = $_COOKIE;
        $_COOKIE = array();
    }

    /**
     * Resets the global state after a test is done
     *
     * @return Null
     */
    public function tearDown ()
    {
        $_COOKIE = $this->cookie;
    }

    public function testGet ()
    {
        $_COOKIE['key'] = "Data";
        $_COOKIE['key2'] = new stdClass;

        $sess = new \h2o\Session\Cookie;

        $this->assertSame( "Data", $sess->get("key") );
        $this->assertSame( $_COOKIE['key2'], $sess->get("key2") );
        $this->assertNull( $sess->get("Not A Key") );
    }

    public function testSet ()
    {
        $this->markTestIncomplete();
    }

    public function testExists ()
    {
        $this->markTestIncomplete();
    }

    public function testClear ()
    {
        $this->markTestIncomplete();
    }

    public function testClearAll ()
    {
        $this->markTestIncomplete();
    }

    public function testGetAll ()
    {
        $this->markTestIncomplete();
    }

}

?>