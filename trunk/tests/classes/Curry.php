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
    
    public function testOffset ()
    {
        
        $curry = new cPHP::Curry::Call( "trim" );
        
        $this->assertEquals( 0, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->setOffset( 1 ) );
        
        $this->assertEquals( 1, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->clearOffset() );
        
        $this->assertEquals( 0, $curry->getOffset() );
        
        $this->assertSame( $curry, $curry->setOffset( 5 ) );
        
        $this->assertEquals( 5, $curry->getOffset() );
    }
    
    public function testLimit ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        
        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->setLimit( 2 ) );
        
        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 2, $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->clearLimit() );
        
        $this->assertFalse( $curry->issetLimit() );
        $this->assertFalse( $curry->getLimit() );
        
        $this->assertSame( $curry, $curry->setLimit( 5 ) );
        
        $this->assertTrue( $curry->issetLimit() );
        $this->assertEquals( 5, $curry->getLimit() );
    }
    
    public function testClearSlicing ()
    {
        
        $curry = new cPHP::Curry::Call( "trim" );
        $curry->setLimit( 1 );
        $curry->setOffset( 1 );
        
        $this->assertSame( $curry, $curry->clearSlicing() );
        
        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );
    }
    
    public function testClear ()
    {
        
        $curry = new cPHP::Curry::Call( "trim" );
        $curry->setRight("wakka", "peanut");
        $curry->setLeft("bean", "orange");
        $curry->setLimit( 1 );
        $curry->setOffset( 1 );
        
        
        $this->assertSame( $curry, $curry->clear() );
        
        $this->assertEquals( array(), $curry->getLeft() );
        $this->assertEquals( array(), $curry->getRight() );  
        $this->assertEquals( 0, $curry->getOffset() );
        $this->assertFalse( $curry->issetLimit() );
        
    }
    
    public function testCollectArgs ()
    {
        $curry = new cPHP::Curry::Call( "trim" );
        
        $this->assertEquals(
                array(1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setLeft("l1", "l2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setRight("r1", "r2");
        $this->assertEquals(
                array("l1", "l2", 1, 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setOffset( 1 );
        $this->assertEquals(
                array("l1", "l2", 2, 3, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->setLimit( 1 );
        
        $this->assertEquals(
                array("l1", "l2", 2, "r1", "r2"),
                $curry->collectArgs( array(1, 2, 3) )
            );
        
        $curry->clear();
        
        $curry->setLimit( 2 );
        $this->assertEquals(
                array(1, 2),
                $curry->collectArgs( array(1, 2, 3) )
            );
    }
    
}

?>