<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_db_result extends PHPUnit_Framework_TestCase
{
    public function testGetQuery ()
    {
        $mock = $this->getMock(
                "cPHP::DB::Result",
                array("rawFree"),
                array("SELECT * FROM table")
            );
        
        $this->assertSame(
                "SELECT * FROM table",
                $mock->getQuery()
            );
    }
    
}

?>