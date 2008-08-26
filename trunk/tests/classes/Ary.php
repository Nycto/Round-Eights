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
class classes_ary
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('commonPHP Array Class');
        $suite->addTestSuite( 'general' );
        $suite->addTestSuite( 'classes_ary_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_ary_tests extends PHPUnit_Framework_TestCase
{
    
    public function testCreate ()
    {
        $ary = cPHP::Ary::create(array( 4, 3, "other"));
        
        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );
    }
    
    public function testRange ()
    {
        $ary = cPHP::Ary::range(5, 8);
        
        $this->assertEquals(
                array(5, 6, 7, 8),
                $ary->get()
            );
    }
    
    public function testIteration ()
    {
        
        $ary = new cPHP::Ary(array(
                1 => 1,
                0 => 0,
                NULL => NULL,
                FALSE => FALSE,
                TRUE => TRUE,
                "string" => "string",
                "0" => "0"
            ));
        
        $iter = array();
        foreach ( $ary AS $key => $val ) {
            $iter[ $key ] = $val;
        }
        
        $this->assertEquals(
                array(
                        1 => 1,
                        0 => 0,
                        NULL => NULL,
                        FALSE => FALSE,
                        TRUE => TRUE,
                        "string" => "string",
                        "0" => "0"
                    ),
                $iter
            );
        
    }
    
    public function testCount ()
    {
        $ary = new cPHP::Ary( array(3 => 1, 2 => 2, 1 => 3) );
        $this->assertEquals( 3, count($ary) );
    }
    
    public function testAccess ()
    {
        $ary = new cPHP::Ary( array(3 => 1, 2 => 2, 1 => 3) );
        
        $this->assertEquals( 2, $ary[2] );
        
        
        $ary[0] = 4;
        $this->assertEquals( 4, $ary[0] );
        
        
        $ary[] = 5;
        $this->assertEquals( 5, $ary[4] );
        
        
        $this->assertTrue( isset($ary[1]) );
        $this->assertFalse( isset($ary[10]) );
        
        
        unset ( $ary[1] );
        $this->assertFalse( isset($ary[1]) );
        
        
        $this->assertEquals( 4, count($ary) );
        
    }
    
    public function testCalcOffset ()
    {
        
        /* Disabled while the custom exceptions are developed
        $this->assertFalse(
                cPHP::Ary::create(array())->calcOffset(2, cPHP::Ary::OFFSET_NONE)
            );
        */

        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-5, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(-2, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(-1, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(0, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(3, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(4, cPHP::Ary::OFFSET_NONE) );

        /* Disabled while the custom exceptions are developed
        $this->assert_false( cPHP::Ary::range(1, 5)->calcOffset(5, cPHP::Ary::OFFSET_NONE) );
        $this->assert_false( cPHP::Ary::range(1, 5)->calcOffset(-6, cPHP::Ary::OFFSET_NONE) );
        */

        $this->assertEquals(1, cPHP::Ary::range(1, 5)->calcOffset(-14, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(2, cPHP::Ary::range(1, 5)->calcOffset(-8, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-5, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(-2, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(-1, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(0, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(3, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(4, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(8, cPHP::Ary::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(15, cPHP::Ary::OFFSET_WRAP) );

        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-14, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-8, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-5, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(-2, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(-1, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(0, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(3, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(4, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(8, cPHP::Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(15, cPHP::Ary::OFFSET_RESTRICT) );

        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-2, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-1, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(0, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(3, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(4, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(8, cPHP::Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(15, cPHP::Ary::OFFSET_LIMIT) );
        
    }
    
    public function testPushPop ()
    {
        $ary = new cPHP::Ary( array(3 => 1, 1 => 3) );
        
        $this->assertSame(
                $ary,
                $ary->push( 4 )
            );
        
        $this->assertEquals(
                array(3 => 1, 1 => 3, 4 => 4),
                $ary->get()
            );
        
        
        $this->assertSame(
                $ary,
                $ary->pop()
            );
        
        $this->assertEquals(
                array(3 => 1, 1 => 3),
                $ary->get()
            );
        
        
        $this->assertEquals(
                3,
                $ary->pop( TRUE )
            );
        
        
        $this->assertSame(
                $ary,
                $ary->pop()->pop()->pop()
            );
        
        $this->assertEquals(
                array(),
                $ary->get()
            );
        
    }
    
    public function testShiftUnshift ()
    {
        $ary = new cPHP::Ary( array(3 => 1, 1 => 3) );
        
        $this->assertSame(
                $ary,
                $ary->unshift( 4 )
            );
        
        $this->assertEquals(
                array(0 => 4, 1 => 1, 2 => 3 ),
                $ary->get()
            );
        
        
        $this->assertSame(
                $ary,
                $ary->shift()
            );
        
        $this->assertEquals(
                array(0 => 1, 1 => 3),
                $ary->get()
            );
                
        
        
        $this->assertEquals(
                1,
                $ary->shift( TRUE )
            );
        
        
        $this->assertSame(
                $ary,
                $ary->shift()->shift()->shift()
            );
        
        $this->assertEquals(
                array(),
                $ary->get()
            );
        
    }
    
    public function testEnd ()
    {
        $ary = cPHP::Ary::range(0, 19);
        
        $this->assertSame(
                $ary,
                $ary->end()
            );
        
        $this->assertEquals(
                19,
                $ary->current()
            );
    }
    
    public function testPrev ()
    {
        $ary = cPHP::Ary::range(0, 19);
        
        $this->assertSame(
                $ary,
                $ary->prev()
            );
        
        $this->assertFalse(
                $ary->valid()
            );
        
        $ary->end();
        
        
        $this->assertSame(
                $ary,
                $ary->prev()
            );
        
        $this->assertEquals(
                18,
                $ary->current()
            );
    }
    
    public function testKeyOffset ()
    {
        $ary = new cPHP::Ary(
                array( 4 => "zero", 2 => "one", 8 => "two", 32 => "three", 16 => "four", 64 => "five"  )
            );
        
        $this->assertEquals( 0, $ary->keyOffset( 4 ) );
        $this->assertEquals( 5, $ary->keyOffset( 64 ) );
        $this->assertEquals( 3, $ary->keyOffset( 32 ) );
    }
    
    public function testPointer ()
    {
        $ary = cPHP::Ary::range(0, 19);
        
        $this->assertEquals( 0, $ary->pointer() );
        
        $ary->end();
        
        $this->assertEquals( 19, $ary->pointer() );
        
        $ary->prev()->prev()->prev();
        
        $this->assertEquals( 16, $ary->pointer() );
    }
    
    public function testOffset ()
    {
        $ary = cPHP::Ary::range(1, 15);
        
        $this->assertEquals(1, $ary->offset(0) );
        $this->assertEquals(15, $ary->offset(-1) );
        $this->assertEquals(8, $ary->offset(7) );
    }
    
    public function testSeek ()
    {
        $ary = cPHP::Ary::range(0, 19);
        
        // Test setting the pointer to the end of the array
        $this->assertSame(
                $ary,
                $ary->seek(-1)
            );
        $this->assertEquals( 19, $ary->current() );
        
        
        // Test setting it to the beginning
        $this->assertSame(
                $ary,
                $ary->seek(0)
            );
        $this->assertEquals( 0, $ary->current() );
        
        
        // Test seeking to the current location of the pointer
        $ary->next()->next()->next();
        $this->assertSame(
                $ary,
                $ary->seek(3)
            );
        $this->assertEquals( 3, $ary->current() );
        
        
        // Test seeking to a point closest to the end of the array
        $this->assertSame(
                $ary,
                $ary->seek(17)
            );
        $this->assertEquals( 17, $ary->current() );
        
        
        // Test seeking to a point closest to the beginning of the array
        $this->assertSame(
                $ary,
                $ary->seek(5)
            );
        $this->assertEquals( 5, $ary->current() );
        
        
        // Test seeking forward when the closest point is the current pointer
        $this->assertSame(
                $ary,
                $ary->seek(7)
            );
        $this->assertEquals( 7, $ary->current() );
        
        
        // Test seeking backward when the closest point is the current pointer
        $this->assertSame(
                $ary,
                $ary->seek(6)
            );
        $this->assertEquals( 6, $ary->current() );
        
    }
    
    public function testKeys ()
    {
        $ary = new cPHP::Ary( array(3 => 10, 1 => 9, 5 => 8, 10 => 7 ) );
        
        $keys = $ary->keys();
        
        $this->assertThat( $keys, $this->isInstanceOf("cPHP::Ary") );
        
        $this->assertEquals(array(3, 1, 5, 10), $keys->get());
    }
    
    public function testValues ()
    {
        $ary = new cPHP::Ary( array(3 => 10, 1 => 9, 5 => 8, 10 => 7 ) );
        
        $keys = $ary->values();
        
        $this->assertThat( $keys, $this->isInstanceOf("cPHP::Ary") );
        
        $this->assertEquals(array(10, 9, 8, 7), $keys->get());
    }
    
    public function testClear ()
    {
        $ary = new cPHP::Ary(array( 4, 3, "other"));
        
        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );
        
        $this->assertSame(
                $ary,
                $ary->clear()
            );
        
        
        $this->assertEquals( array(), $ary->get() );
    }
    
    public function testFlatten ()
    {
        $ary = cPHP::Ary::create( array(array(1,2,3)) )->flatten();
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,6)) )->flatten();
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten();
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten();
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,6,7,8)) )->flatten( 2 );
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,6,7,8)),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten( 3 );
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                $ary->get()
            );


        $ary = cPHP::Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten( 4 );
        
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                $ary->get()
            );
        
    }
    
    public function testSort ()
    {
        $ary = cPHP::Ary::create(array( 3 => "d", 5 => "b", 6 => "a", 1 => "f", 4 => "c", 2 => "e" ));
        
        $this->assertSame( $ary, $ary->sort() );
        $this->assertEquals(
                array( 6 => "a", 5 => "b", 4 => "c", 3 => "d", 2 => "e", 1 => "f" ),
                $ary->get()
            );
        
        
        $ary = cPHP::Ary::create(array( 3 => "d", 5 => "b", 6 => "a", 1 => "f", 4 => "c", 2 => "e" ));
        
        $this->assertSame( $ary, $ary->sort( TRUE ) );
        $this->assertEquals(
                array( 1 => 'f', 2 => 'e', 3 => 'd', 4 => 'c', 5 => 'b', 6 => 'a' ),
                $ary->get()
            );
        
    }
    
}

?>