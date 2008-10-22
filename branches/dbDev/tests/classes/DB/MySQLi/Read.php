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
class classes_db_mysqli_read extends PHPUnit_MySQLi_Framework_TestCase
{
    
    public function testCount ()
    {
        $link = $this->getLink();
        
        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        $this->assertSame( 3, $result->count() );
    }
    
    public function testIteration ()
    {
        $link = $this->getLink();
        
        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        
        $copy = array();
        foreach($result AS $key => $value) {
            $copy[$key] = $value;
        }
        
        $this->assertSame(
                array(
                        array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                        array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                        array('id' => '3', 'label' => 'gamma', 'data' => 'three')
                    ),
                $copy
            );
        
        
        $copy = array();
        foreach($result AS $key => $value) {
            $copy[$key] = $value;
        }
        
        $this->assertSame(
                array(
                        array('id' => '1', 'label' => 'alpha', 'data' => 'one'),
                        array('id' => '2', 'label' => 'beta', 'data' => 'two'),
                        array('id' => '3', 'label' => 'gamma', 'data' => 'three')
                    ),
                $copy
            );
    }
    
    public function testFields ()
    {
        $link = $this->getLink();
        
        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        $fields = $result->getFields();
        
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        
        $this->assertSame(
                array('id', 'label', 'data'),
                $fields->get()
            );
    }
    
    public function testFree ()
    {
        $link = $this->getLink();
        
        $result = $link->query("SELECT * FROM ". MYSQLI_TABLE);
        
        $this->assertThat( $result, $this->isInstanceOf("cPHP::DB::MySQLi::Read") );
        
        $this->assertTrue( $result->hasResult() );
        
        $this->assertSame( $result, $result->free() );
        
        $this->assertFalse( $result->hasResult() );
    }
    
}

?>