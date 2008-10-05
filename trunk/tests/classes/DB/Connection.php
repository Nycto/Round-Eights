<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../general.php";

/**
 * test suite
 */
class classes_db_connection
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Connection Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_connection_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_connection_tests extends PHPUnit_Framework_TestCase
{
    public function getMockConnection ( $args = array() )
    {
        return $this->getMock(
                "cPHP::DB::Connection",
                array("rawConnect", "rawDisconnect", "rawEscape", "rawQuery"),
                $args
            );
    }
    
    public function testPersistentAccessors ()
    {
        
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->getPersistent() );
        
        $this->assertSame( $mock, $mock->setPersistent(TRUE) );
        $this->assertTrue( $mock->getPersistent() );
        
        $this->assertSame( $mock, $mock->setPersistent("off") );
        $this->assertFalse( $mock->getPersistent() );
        
        $this->assertSame( $mock, $mock->setPersistent("on") );
        $this->assertTrue( $mock->getPersistent() );
        
    }
    
    public function testForceNewAccessors ()
    {
        
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->getForceNew() );
        
        $this->assertSame( $mock, $mock->setForceNew(TRUE) );
        $this->assertTrue( $mock->getForceNew() );
        
        $this->assertSame( $mock, $mock->setForceNew("off") );
        $this->assertFalse( $mock->getForceNew() );
        
        $this->assertSame( $mock, $mock->setForceNew("on") );
        $this->assertTrue( $mock->getForceNew() );
        
    }
    
    public function testUsernameAccessors ()
    {
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );
        
        $this->assertSame( $mock, $mock->setUserName("uname") );
        $this->assertTrue( $mock->userNameExists() );
        $this->assertSame( "uname", $mock->getUserName() );
        
        $this->assertSame( $mock, $mock->clearUserName() );
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );
        
        $this->assertSame( $mock, $mock->setUserName("uname") );
        $this->assertTrue( $mock->userNameExists() );
        $this->assertSame( "uname", $mock->getUserName() );
        
        $this->assertSame( $mock, $mock->setUserName("  ") );
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );
    }
    
    public function testPasswordAccessors ()
    {
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );
        
        $this->assertSame( $mock, $mock->setPassword("pword") );
        $this->assertTrue( $mock->passwordExists() );
        $this->assertSame( "pword", $mock->getPassword() );
        
        $this->assertSame( $mock, $mock->clearPassword() );
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );
        
        $this->assertSame( $mock, $mock->setPassword("pword") );
        $this->assertTrue( $mock->passwordExists() );
        $this->assertSame( "pword", $mock->getPassword() );
        
        $this->assertSame( $mock, $mock->setPassword("   ") );
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );
    }
    
    public function testHostAccessors ()
    {
        $mock = $this->getMockConnection();
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "localhost", $mock->getHost() );
        
        $this->assertSame( $mock, $mock->setHost("127.0.0.1") );
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "127.0.0.1", $mock->getHost() );
        
        $this->assertSame( $mock, $mock->clearHost() );
        $this->assertFalse( $mock->hostExists() );
        $this->assertNull( $mock->getHost() );
        
        $this->assertSame( $mock, $mock->setHost("127.0.0.1") );
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "127.0.0.1", $mock->getHost() );
        
        $this->assertSame( $mock, $mock->setHost("   ") );
        $this->assertFalse( $mock->hostExists() );
        $this->assertNull( $mock->getHost() );
    }
    
    public function testPortAccessors ()
    {
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );
        
        $this->assertSame( $mock, $mock->setPort(80) );
        $this->assertTrue( $mock->portExists() );
        $this->assertSame( 80, $mock->getPort() );
        
        $this->assertSame( $mock, $mock->clearPort() );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );
        
        $this->assertSame( $mock, $mock->setPort("100") );
        $this->assertTrue( $mock->portExists() );
        $this->assertSame( 100, $mock->getPort() );
        
        $this->assertSame( $mock, $mock->setPort( 0 ) );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );
        
        $this->assertSame( $mock, $mock->setPort( -50 ) );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );
    }
    
    public function testDatabaseAccessors ()
    {
        $mock = $this->getMockConnection();
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );
        
        $this->assertSame( $mock, $mock->setDatabase("dbase") );
        $this->assertTrue( $mock->databaseExists() );
        $this->assertSame( "dbase", $mock->getDatabase() );
        
        $this->assertSame( $mock, $mock->clearDatabase() );
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );
        
        $this->assertSame( $mock, $mock->setDatabase("dbase") );
        $this->assertTrue( $mock->databaseExists() );
        $this->assertSame( "dbase", $mock->getDatabase() );
        
        $this->assertSame( $mock, $mock->setDatabase("   ") );
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );
    }
    
    public function testValidateCredentials ()
    {
        $mock = $this->getMockConnection();
        $mock->setUserName("uname")
            ->setHost("localhost")
            ->setDatabase("dbase");
        
        $this->assertSame( $mock, $mock->validateCredentials() );
        
        $mock = $this->getMockConnection();
        $mock->clearHost();
        
        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Database::Connection $err ) {
            $this->assertSame( "UserName must be set", $err->getMessage() );
        }
        
        $mock->setUserName("uname");
        
        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Database::Connection $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }
        
        $mock->setHost("127.0.0.1");
        
        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Database::Connection $err ) {
            $this->assertSame( "Database name must be set", $err->getMessage() );
        }
        
        $mock->setDatabase("dbname");
        
        $this->assertSame( $mock, $mock->validateCredentials() );
    }
    
    public function testGetHostWithPort ()
    {
        $mock = $this->getMockConnection();
        $mock->clearHost();
        
        try {
            $mock->getHostWithPort();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Interaction $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }
        
        $mock->setHost("localhost");
        $this->assertSame("localhost", $mock->getHostWithPort());
        
        $mock->setPort(80);
        $this->assertSame("localhost:80", $mock->getHostWithPort());
    }
    
}

?>