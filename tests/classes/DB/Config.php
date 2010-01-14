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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_DB_Config extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $config = new \r8\DB\Config( "db://example.com/datab" );

        $this->assertSame( "example.com", $config->getHost() );
        $this->assertSame( "datab", $config->getDatabase() );


        $config = new \r8\DB\Config( array( "host" => "db.com", "port" => 42 ) );

        $this->assertSame( "db.com", $config->getHost() );
        $this->assertSame( 42, $config->getPort() );
    }

    public function testPersistentAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertFalse( $config->getPersistent() );

        $this->assertSame( $config, $config->setPersistent(TRUE) );
        $this->assertTrue( $config->getPersistent() );

        $this->assertSame( $config, $config->setPersistent("off") );
        $this->assertFalse( $config->getPersistent() );

        $this->assertSame( $config, $config->setPersistent("on") );
        $this->assertTrue( $config->getPersistent() );

    }

    public function testForceNewAccessors ()
    {

        $config = new \r8\DB\Config;
        $this->assertFalse( $config->getForceNew() );

        $this->assertSame( $config, $config->setForceNew(TRUE) );
        $this->assertTrue( $config->getForceNew() );

        $this->assertSame( $config, $config->setForceNew("off") );
        $this->assertFalse( $config->getForceNew() );

        $this->assertSame( $config, $config->setForceNew("on") );
        $this->assertTrue( $config->getForceNew() );

    }

    public function testUsernameAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertFalse( $config->userNameExists() );
        $this->assertNull( $config->getUserName() );

        $this->assertSame( $config, $config->setUserName("uname") );
        $this->assertTrue( $config->userNameExists() );
        $this->assertSame( "uname", $config->getUserName() );

        $this->assertSame( $config, $config->clearUserName() );
        $this->assertFalse( $config->userNameExists() );
        $this->assertNull( $config->getUserName() );

        $this->assertSame( $config, $config->setUserName("uname") );
        $this->assertTrue( $config->userNameExists() );
        $this->assertSame( "uname", $config->getUserName() );

        $this->assertSame( $config, $config->setUserName("  ") );
        $this->assertFalse( $config->userNameExists() );
        $this->assertNull( $config->getUserName() );
    }

    public function testPasswordAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertFalse( $config->passwordExists() );
        $this->assertNull( $config->getPassword() );

        $this->assertSame( $config, $config->setPassword("pword") );
        $this->assertTrue( $config->passwordExists() );
        $this->assertSame( "pword", $config->getPassword() );

        $this->assertSame( $config, $config->clearPassword() );
        $this->assertFalse( $config->passwordExists() );
        $this->assertNull( $config->getPassword() );

        $this->assertSame( $config, $config->setPassword("pword") );
        $this->assertTrue( $config->passwordExists() );
        $this->assertSame( "pword", $config->getPassword() );

        $this->assertSame( $config, $config->setPassword("   ") );
        $this->assertFalse( $config->passwordExists() );
        $this->assertNull( $config->getPassword() );
    }

    public function testHostAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertTrue( $config->hostExists() );
        $this->assertSame( "localhost", $config->getHost() );

        $this->assertSame( $config, $config->setHost("127.0.0.1") );
        $this->assertTrue( $config->hostExists() );
        $this->assertSame( "127.0.0.1", $config->getHost() );

        $this->assertSame( $config, $config->clearHost() );
        $this->assertFalse( $config->hostExists() );
        $this->assertNull( $config->getHost() );

        $this->assertSame( $config, $config->setHost("127.0.0.1") );
        $this->assertTrue( $config->hostExists() );
        $this->assertSame( "127.0.0.1", $config->getHost() );

        $this->assertSame( $config, $config->setHost("   ") );
        $this->assertFalse( $config->hostExists() );
        $this->assertNull( $config->getHost() );
    }

    public function testPortAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertFalse( $config->portExists() );
        $this->assertNull( $config->getPort() );

        $this->assertSame( $config, $config->setPort(80) );
        $this->assertTrue( $config->portExists() );
        $this->assertSame( 80, $config->getPort() );

        $this->assertSame( $config, $config->clearPort() );
        $this->assertFalse( $config->portExists() );
        $this->assertNull( $config->getPort() );

        $this->assertSame( $config, $config->setPort("100") );
        $this->assertTrue( $config->portExists() );
        $this->assertSame( 100, $config->getPort() );

        $this->assertSame( $config, $config->setPort( 0 ) );
        $this->assertFalse( $config->portExists() );
        $this->assertNull( $config->getPort() );

        $this->assertSame( $config, $config->setPort( -50 ) );
        $this->assertFalse( $config->portExists() );
        $this->assertNull( $config->getPort() );
    }

    public function testDatabaseAccessors ()
    {
        $config = new \r8\DB\Config;
        $this->assertFalse( $config->databaseExists() );
        $this->assertNull( $config->getDatabase() );

        $this->assertSame( $config, $config->setDatabase("dbase") );
        $this->assertTrue( $config->databaseExists() );
        $this->assertSame( "dbase", $config->getDatabase() );

        $this->assertSame( $config, $config->clearDatabase() );
        $this->assertFalse( $config->databaseExists() );
        $this->assertNull( $config->getDatabase() );

        $this->assertSame( $config, $config->setDatabase("dbase") );
        $this->assertTrue( $config->databaseExists() );
        $this->assertSame( "dbase", $config->getDatabase() );

        $this->assertSame( $config, $config->setDatabase("   ") );
        $this->assertFalse( $config->databaseExists() );
        $this->assertNull( $config->getDatabase() );
    }

    public function testRequireCredentials ()
    {
        $config = new \r8\DB\Config;
        $config->setUserName("uname")
            ->setHost("localhost")
            ->setDatabase("dbase");

        $this->assertSame( $config, $config->requireCredentials() );

        $config = new \r8\DB\Config;
        $config->clearHost();

        try {
            $config->requireCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertSame( "UserName must be set", $err->getMessage() );
        }

        $config->setUserName("uname");

        try {
            $config->requireCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }

        $config->setHost("127.0.0.1");

        try {
            $config->requireCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertSame( "Database name must be set", $err->getMessage() );
        }

        $config->setDatabase("dbname");

        $this->assertSame( $config, $config->requireCredentials() );
    }

    public function testGetHostWithPort ()
    {
        $config = new \r8\DB\Config;
        $config->clearHost();

        try {
            $config->getHostWithPort();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }

        $config->setHost("localhost");
        $this->assertSame("localhost", $config->getHostWithPort());

        $config->setPort(80);
        $this->assertSame("localhost:80", $config->getHostWithPort());
    }

    public function testFromArray ()
    {
        $config = new \r8\DB\Config;

        $this->assertSame(
                $config,
                $config->fromArray(array(
                        "host" => "127.0.0.1",
                        "PoRt" => 50,
                        "!@#$  DATABASE" => "dbname"
                    ))
            );

        $this->assertSame( "127.0.0.1", $config->getHost() );
        $this->assertSame( 50, $config->getPort() );
        $this->assertSame( "dbname", $config->getDatabase() );

    }

    public function testFromString ()
    {
        $config = new \r8\DB\Config;

        try {
            $config->fromURI("wakka.com");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "URL is not valid", $err->getMessage() );
        }

        $this->assertSame( $config, $config->fromURI("db://example.com/dbnm") );
        $this->assertSame("example.com", $config->getHost());
        $this->assertSame("dbnm", $config->getDatabase());

        $this->assertSame( $config, $config->fromURI("db://unm:pwd@localhost/otherDB?persistent=on") );
        $this->assertSame("localhost", $config->getHost());
        $this->assertSame("unm", $config->getUserName());
        $this->assertSame("pwd", $config->getPassword());
        $this->assertSame("otherDB", $config->getDatabase());
        $this->assertTrue( $config->getPersistent() );
    }

    public function testGetIdentifier ()
    {
        $config = new \r8\DB\Config;
        $config->clearHost();
        $this->assertSame( "db", $config->getIdentifier("db") );

        $config->setHost("example.com");
        $this->assertSame( "db://example.com", $config->getIdentifier("db") );

        $config->setPort(8080);
        $this->assertSame( "db://example.com:8080", $config->getIdentifier("db") );

        $config->setUserName("uname");
        $this->assertSame( "db://uname@example.com:8080", $config->getIdentifier("db") );
    }

}

?>