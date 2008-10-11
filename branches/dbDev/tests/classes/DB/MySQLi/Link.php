<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * test suite
 */
class classes_db_mysqli_link
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP MySQLi Connection Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_mysqli_link_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_mysqli_link_tests extends PHPUnit_MySQLi_Framework_TestCase
{
    
    public function testConnection ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
    }
    
    public function testConnection_error ()
    {
        $link = new ::cPHP::DB::MySQLi::Link(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            );
        
        try {
            $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::DB::Link $err ) {
            $this->assertContains(
                    "Access denied for user",
                    $err->getMessage()
                );
        }
    }
    
    public function testEscape ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        
        // Escape without a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
        
        $link->getLink();
        
        // Escape WITH a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
    }
    
    public function testQuery ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
    }
    
    public function testDisconnect ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        
        $link->disconnect();
        
        $this->assertFalse( $link->isConnected() );
    }
    
}

?>