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
class classes_db_result_write extends PHPUnit_Framework_TestCase
{
    
    public function testGetAffected ()
    {
        $write = new ::cPHP::DB::Result::Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );
        
        $this->assertSame( 5, $write->getAffected() );
        
        
        $write = new ::cPHP::DB::Result::Write(
                null,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );
        
        $this->assertSame( 0, $write->getAffected() );
        
        
        $write = new ::cPHP::DB::Result::Write(
                -5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );
        
        $this->assertSame( 0, $write->getAffected() );
    }
    
    public function testGetInsertID ()
    {
        $write = new ::cPHP::DB::Result::Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );
        
        $this->assertNull( $write->getInsertID() );
        
        
        $write = new ::cPHP::DB::Result::Write(
                5,
                FALSE,
                "UPDATE table SET field = 'new' LIMIT 5"
            );
        
        $this->assertNull( $write->getInsertID() );
        
        
        $write = new ::cPHP::DB::Result::Write(
                1,
                50,
                "INSERT INTO table SET field = 'new'"
            );
        
        $this->assertSame( 50, $write->getInsertID() );
        
        
        $write = new ::cPHP::DB::Result::Write(
                1,
                -10,
                "INSERT INTO table SET field = 'new'"
            );
        
        $this->assertNull( $write->getInsertID() );
    }
    
}

?>