<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * test suite
 */
class classes_curry
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('commonPHP Curry Class');
        $suite->addTestSuite( 'general' );
        $suite->addTestSuite( 'classes_curry_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_curry_tests extends PHPUnit_Framework_TestCase
{
    
    
    public function testCreate ()
    {
        
        $this->assertThat(
                cPHP::Curry::Call::Create("trim"),
                $this->isInstanceOf("cPHP::Curry::Call")
            );
        
        $instance = cPHP::Curry::Call::Create("trim", "/");
        
        $this->assertThat(
                $instance,
                $this->isInstanceOf("cPHP::Curry::Call")
            );
        
        $this->assertEquals( array("/"), $instance->getRight() );
        
    }
    
    public function testSet ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setRight("wakka", "peanut")
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setLeft("bean", "orange")
            );
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }
    
    public function testSetByArray ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->setRightByArray( array("wakka", "peanut") )
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        
        $this->assertSame(
                $curry,
                $curry->setLeftByArray( array("bean", "orange") )
            );
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
    }
    
    public function testClearLeftRight ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearLeft()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearRight()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );   
    }
    
    public function testClearArgs ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        
        $this->assertEquals( array("bean", "orange"), $curry->getLeft() );
        $this->assertEquals( array("wakka", "peanut"), $curry->getRight() );
        
        $this->assertSame(
                $curry,
                $curry->clearArgs()
            );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );  
    }
    
}

?>