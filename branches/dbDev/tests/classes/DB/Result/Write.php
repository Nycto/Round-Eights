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
class classes_db_result_write
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Write Result Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_result_write_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_result_write_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetAffected_valid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Write",
                array("rawAffected", "rawInsertID", "rawFree"),
                array(null, "UPDATE table SET cTime = NOW()")
            );
        
        $write->expects( $this->once() )
            ->method("rawAffected")
            ->will( $this->returnValue(20) );
        
        $this->assertSame( 20, $write->getAffected() );
        $this->assertSame( 20, $write->getAffected() );
        $this->assertSame( 20, $write->getAffected() );
    }
    
    public function testGetAffected_invalid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Write",
                array("rawAffected", "rawInsertID", "rawFree"),
                array(null, "UPDATE table SET cTime = NOW()")
            );
        
        $write->expects( $this->once() )
            ->method("rawAffected")
            ->will( $this->returnValue(null) );
        
        $this->assertFalse( $write->getAffected() );
        $this->assertFalse( $write->getAffected() );
        $this->assertFalse( $write->getAffected() );
    }
    
    public function testGetInsertID_valid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Write",
                array("rawAffected", "rawInsertID", "rawFree"),
                array(null, "UPDATE table SET cTime = NOW()")
            );
        
        $write->expects( $this->once() )
            ->method("rawInsertID")
            ->will( $this->returnValue(101) );
        
        $this->assertSame( 101, $write->getInsertID() );
        $this->assertSame( 101, $write->getInsertID() );
        $this->assertSame( 101, $write->getInsertID() );
    }
    
    public function testGetInsertID_invalid ()
    {
        $write = $this->getMock(
                "cPHP::DB::Result::Write",
                array("rawAffected", "rawInsertID", "rawFree"),
                array(null, "UPDATE table SET cTime = NOW()")
            );
        
        $write->expects( $this->once() )
            ->method("rawInsertID")
            ->will( $this->returnValue(null) );
        
        $this->assertFalse( $write->getInsertID() );
        $this->assertFalse( $write->getInsertID() );
        $this->assertFalse( $write->getInsertID() );
    }
    
}

?>