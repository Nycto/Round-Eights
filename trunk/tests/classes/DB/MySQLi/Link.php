<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

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
        $link = $this->getLink();
        
        // Escape without a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
        
        $link->getLink();
        
        // Escape WITH a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
    }
    
    public function testQuery_read ()
    {
        $link = $this->getLink();
        
        $result = $link->query("SELECT 50 + 10");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
    }
    
    public function testQuery_write ()
    {
        $link = $this->getLink();
        
        $result = $link->query("UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1");
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::Result::Write") );
        
        $this->assertSame(
                "UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1",
                $result->getQuery()
            );
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