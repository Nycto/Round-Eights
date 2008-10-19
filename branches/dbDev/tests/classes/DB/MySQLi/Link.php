<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_db_mysqli_link extends PHPUnit_MySQLi_Framework_TestCase
{
    
    public function testConnection_error ()
    {
        $link = new ::cPHP::DB::MySQLi::Link(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            );
        
        try {
            $link->getLink();
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::DB::Link $err ) {
            $this->assertContains(
                    "Access denied for user",
                    $err->getMessage()
                );
        }
    }
    
    public function testConnection ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
        $this->assertTrue( $link->isConnected() );
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
    
    public function testQuery_read ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        
        $result = $link->query("SELECT 50 + 10");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
        
        $raw = $result->getResult();
        $this->assertThat( $raw, $this->isInstanceOf("mysqli_result") );
        $this->assertSame( 1, $raw->num_rows );
        $this->assertEquals( array(60), $raw->fetch_row() );
        $raw->free();
    }
    
    public function testDisconnect ()
    {
        $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
        $link->getLink();
        
        $this->assertTrue( $link->isConnected() );
        
        $this->assertSame( $link, $link->disconnect() );
        
        $this->assertFalse( $link->isConnected() );
    }
    
}

?>