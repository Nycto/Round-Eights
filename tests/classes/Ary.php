<?php
/**
 * Unit Test File
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * A stub used for callbacks
 */
class stub_classes_ary_boolCallbacks
{

    static public function callbackStatic ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new ::cPHP::Exception::Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

    public function callbackObject ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new ::cPHP::Exception::Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

    public function __invoke ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new ::cPHP::Exception::Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

}

/**
 * unit tests
 */
class classes_ary extends PHPUnit_Framework_TestCase
{

    public function callbackObject ()
    {
        $args = func_get_args();
        return "o". implode(":", $args);
    }

    static public function callbackStatic ()
    {
        $args = func_get_args();
        return "s". implode(":", $args);
    }

    public function __invoke ()
    {
        $args = func_get_args();
        return "i". implode(":", $args);
    }

    public function testConstruct ()
    {
        $ary = new cPHP::Ary(array( 4, 3, "other"));
        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );


        $newAry = new cPHP::Ary( $ary );
        $this->assertNotSame( $ary, $newAry );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $newAry->get()
            );


        $iterAggr = $this->getMock("IteratorAggregate", array("getIterator"));
        $iterAggr->expects( $this->once() )
            ->method("getIterator")
            ->with()
            ->will( $this->returnValue( new ArrayObject( array( 4, 3, "other" ) ) ) );

        $ary = new cPHP::Ary( $iterAggr );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $ary->get()
            );


        $iter = new ArrayIterator( array( 4, 3, "other" ) );
        $ary = new cPHP::Ary( $iter );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $ary->get()
            );


        $ary = new cPHP::Ary( "string" );
        $this->assertEquals(
                array( "string" ),
                $ary->get()
            );


        $ary = new cPHP::Ary( 1 );
        $this->assertEquals(
                array( 1 ),
                $ary->get()
            );

    }

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

    public function testExplode ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testIs ()
    {
        $this->assertTrue( ::cPHP::Ary::is(array()) );
        $this->assertTrue( ::cPHP::Ary::is( new ::cPHP::Ary ) );
        $this->assertTrue( ::cPHP::Ary::is( new ArrayObject ) );

        $this->assertFalse( ::cPHP::Ary::is(5) );
        $this->assertFalse( ::cPHP::Ary::is(5.0) );
        $this->assertFalse( ::cPHP::Ary::is("string") );
        $this->assertFalse( ::cPHP::Ary::is(FALSE) );
        $this->assertFalse( ::cPHP::Ary::is(TRUE) );
        $this->assertFalse( ::cPHP::Ary::is(NULL) );
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
        try {
            cPHP::Ary::create(array())->calcOffset(2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}


        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(-5, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(-2, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(-1, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(0, cPHP::Ary::range(1, 5)->calcOffset(0, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::Ary::range(1, 5)->calcOffset(3, cPHP::Ary::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::Ary::range(1, 5)->calcOffset(4, cPHP::Ary::OFFSET_NONE) );

        try {
            cPHP::Ary::create(array())->calcOffset(2, cPHP::Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}

        try {
            cPHP::Ary::range(1, 5)->calcOffset(5, cPHP::Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}

        try {
            cPHP::Ary::range(1, 5)->calcOffset(-6, cPHP::Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}


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

        try {
            cPHP::Ary::range(1, 5)->keyOffset(60);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}
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

    public function testFirst ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testLast ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testKeyExists ()
    {
        $this->assertTrue( cPHP::Ary::range(1, 15)->keyExists(0) );
        $this->assertFalse( cPHP::Ary::range(1, 15)->keyExists(15) );

        $this->assertFalse( cPHP::Ary::create(array("key" => "value"))->keyExists(14) );
        $this->assertTrue( cPHP::Ary::create(array("key" => "value"))->keyExists("key") );
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

    public function testContains ()
    {
        $this->markTestIncomplete("To be written");
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


        $ary = cPHP::Ary::create( array( 1 => 'one', 2 => 'two' ) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array( 1 => 'one', 2 => 'two' ),
                $ary->get()
            );

    }

    public function testFlatten_maxDepth ()
    {


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




        $ary = cPHP::Ary::create(array(
                new cPHP::Ary(array(1,2,3)),
                array(4,5, new cPHP::Ary(array(6,7,8)) )
            ))->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = cPHP::Ary::create(array(
                new cPHP::Ary(array(1,2,3)),
                array(4,5, new cPHP::Ary(array(6,7,8)) )
            ))->flatten( 3 );

        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(1,2,3),
                $ary->offsetGet(0)->get()
            );

        $subAry = $ary->offsetGet(1);
        $this->assertType("array", $subAry);
        $this->assertEquals(
                array(6, 7, 8),
                $subAry[2]->get()
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


        try {
            $ary->sort( TRUE, "Invalid sort type" );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}
    }

    public function testSortyByKey ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testNaturalSort ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testCustomSort ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testReverse ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testShuffle ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testBubbleKeys ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testHone ()
    {
        $ary = new ::cPHP::Ary(array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ));

        $result = $ary->hone('five', 'three', 'six');
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );

        // Make sure the original array remains unchanged
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ),
                $ary->get()
            );

        $this->assertEquals(
                array ( 'five' => 5, 'three' => 3, 'six' => 1 ),
                $result->get()
            );


        $result = $ary->hone( array('ten', 'eleven'), 'twelve');
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array ( 'ten' => 1, 'eleven' => 2, 'twelve' => 3 ),
                $result->get()
            );


        $result = $ary->hone( array('seven', 'six'), 'five', array(array('four')));
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array ( 'seven' => 1, 'six' => 2, 'five' => 5, 'four' => 4 ),
                $result->get()
            );
    }

    public function testTranslateKeys ()
    {
        $ary = new ::cPHP::Ary(array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ));

        $result = $ary->translateKeys(array('one' => 'eno', 'three' => 'eerht', 'four' => 'ruof'));
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );

        // Make sure the original array remains unchanged
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ),
                $ary->get()
            );

        $this->assertEquals(
                array( 'eno' => 1, 'two' => 2, 'eerht' => 3, 'ruof' => 4, 'five' => 5 ),
                $result->get()
            );


        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'six' => 3, 'four' => 4, 'five' => 5 ),
                $ary->translateKeys(array('one' => 'five', 'three' => 'six'))->get()
            );


        try {
            $ary->translateKeys("This is not a valid key map");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertEquals("Must be an array or a cPHP::Ary object", $err->getMessage());
        }


        try {
            $ary->translateKeys(array("five" => NULL) );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {
            $this->assertEquals("Invalid key value", $err->getMessage());
        }


        try {
            $ary->translateKeys(array("five" => $this->getMock("stub_translateKeys")) );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Data $err ) {
            $this->assertEquals("Invalid key value", $err->getMessage());
        }
    }

    public function testChangeKeyCase ()
    {
        $ary = new ::cPHP::Ary(array( 'One' => 1, 'Two' => 2, 'Three' => 3 ));

        $result = $ary->changeKeyCase();

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3 ),
                $result->get()
            );


        $result = $ary->changeKeyCase( CASE_LOWER );

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3 ),
                $result->get()
            );


        $result = $ary->changeKeyCase( CASE_UPPER );

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'ONE' => 1, 'TWO' => 2, 'THREE' => 3 ),
                $result->get()
            );
    }

    public function testImplode ()
    {
        $ary = new cPHP::Ary(array(5, "string", "other"));

        $this->assertEquals( "5stringother", $ary->implode() );
        $this->assertEquals( "5, string, other", $ary->implode(", ") );
    }

    public function testCollect ()
    {

        $this->assertEquals(
                array(50, 90),
                cPHP::Ary::create( array( "50", "90") )->collect("floatval")->get()
            );

        $lambda = function ( $value, $key ) { return "$key:$value"; };
        $this->assertEquals(
                array("0:50", "1:90"),
                cPHP::Ary::create( array( "50", "90") )->collect($lambda)->get()
            );

        $this->assertEquals(
                array( 1 => "o50:1", 3 => "o90:3" ),
                cPHP::Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect(array($this, "callbackObject"))
                    ->get()
            );

        $this->assertEquals(
                array( 1 => "s50:1", 3 => "s90:3" ),
                cPHP::Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect(array(__CLASS__, "callbackStatic"))
                    ->get()
            );

        $this->assertEquals(
                array( 1 => "i50:1", 3 => "i90:3" ),
                cPHP::Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect( $this )
                    ->get()
            );

        try {
            cPHP::Ary::create()->collect("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}
    }

    public function testFilter ()
    {

        $oddTest = function( $first, $second ) {
            return $first % 2 == 0;
        };

        $ary = new cPHP::Ary( array(1, 2, 3, 4, 5 ) );

        $this->assertEquals(
                array(1 => 2, 3 => 4),
                $ary->filter( $oddTest )->get()
            );


        $ary = new cPHP::Ary( array("1", "2", 3, 4, "5" ) );

        $this->assertEquals(
                array(0 => "1", 1 => "2", 4 => "5"),
                $ary->filter( "is_string" )->get()
            );


        try {
            cPHP::Ary::create()->filter("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}

    }

    public function testEach ()
    {
        $result = array();
        $lambda = function ( $value, $key ) use ( &$result ) {
            $result[ $value ] = $key;
        };

        $ary = cPHP::Ary::create( array( "one" => 50, "two" => 90 ) );

        $this->assertSame( $ary, $ary->each( $lambda ) );
        $this->assertEquals( array( 50 => "one", 90 => "two"), $result );
        $this->assertEquals( array( "one" => 50, "two" => 90 ), $ary->get() );

        $ary->each($this);
        $ary->each(array( $this, "callbackObject" ));
        $ary->each(array( __CLASS__, "callbackStatic" ));

        try {
            $ary->each("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {}

    }

    public function testInject ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testCompact ()
    {

        $ary = new cPHP::Ary( array( 0, TRUE, NULL, "string", FALSE, 1, array(), array(1.5, ""), "  ", "0" ) );

        $this->assertEquals(
                array( 1 => TRUE, 3 => "string", 5 => 1, 7 => array(1.5), 9 => "0"),
                $ary->compact()->get()
            );

        $this->assertEquals(
                array( 1 => TRUE, 2 => NULL, 3 => "string", 4 => FALSE, 5 => 1, 7 => array(1.5), 9 => "0"),
                $ary->compact( ::cPHP::ALLOW_FALSE | ::cPHP::ALLOW_NULL )->get()
            );


        $ary = new cPHP::Ary(array(
                new cPHP::Ary(array("full", "of", "stuff", FALSE)),
                new cPHP::Ary,
                array(1.5, ""),
            ));

        $ary = $ary->compact();
        $this->assertThat( $ary, $this->isInstanceOf("cPHP::Ary") );

        $ary = $ary->get();

        $this->assertArrayHasKey( 0, $ary );
        $this->assertArrayHasKey( 2, $ary );
        $this->assertArrayNotHasKey( 1, $ary );

        $this->assertThat( $ary[0], $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array("full", "of", "stuff"),
                $ary[0]->get()
            );

        $this->assertType("array", $ary[2]);
        $this->assertEquals( array(1.5), $ary[2] );

    }

    public function testPluck ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testInvoke ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testUnique ()
    {
        $ary = new cPHP::Ary(array(1, 2, 4, 2, 9, 1, 6 ));

        $this->assertThat( $ary->unique(), $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(
                array(0 => 1, 1 => 2, 2 => 4, 4 => 9, 6 => 6 ),
                $ary->unique()->get()
            );
    }

    public function testMerge ()
    {
        $ary = new cPHP::Ary( array( 1, 2, "con" => 4 ) );

        $merged = $ary->merge( array( 5, 6, "con" => 7 ) );

        $this->assertThat( $merged, $this->isInstanceOf( "cPHP::Ary" ) );

        $this->assertEquals(
                array( 1, 2, 5, 6, "con" => 7 ),
                $merged->get()
            );
    }

    public function testAdd()
    {
        $ary = new cPHP::Ary( array( 1, 2, "con" => 4 ) );

        $merged = $ary->add( array( 2 => 3, "con" => 7 ) );

        $this->assertThat( $merged, $this->isInstanceOf( "cPHP::Ary" ) );

        $this->assertEquals(
                array( 1, 2, 3, "con" => 4 ),
                $merged->get()
            );
    }

    public function testAny ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testAll ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testFind ()
    {

        // Test a closure callback
        $ary = new cPHP::Ary( range(0, 10) );
        $result = $ary->find(function ( $value, $key ) {
            return $value == 6 ? TRUE : FALSE;
        });
        $this->assertSame( 6, $result );


        // Test a closure without a match
        $ary = new cPHP::Ary( range(0, 10) );
        $result = $ary->find(function ( $value, $key ) {
            return $value == 50 ? TRUE : FALSE;
        });
        $this->assertFalse( $result );


        // Test a function name as a callback
        $ary = new cPHP::Ary( range(5, -5) );
        $result = $ary->find("cPHP::num::negative");
        $this->assertSame( -1, $result );


        $ary = new cPHP::Ary( array(5, 4, "yes", 3, "also yes", 1) );


        // Static method callback
        $result = $ary->find(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertSame( "yes", $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertSame(
                "yes",
                $ary->find(array($callback, "callbackObject"))
            );

        // Callable object callback
        $this->assertSame( "yes", $ary->find($callback) );

        // invoked without a proper callback
        try {
            $ary->find("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame("Must be callable", $err->getMessage());
        }

    }

    public function testSearch ()
    {
        $ary = new ::cPHP::Ary(array( 0 => 4, 5 => 3, "key" => "other"));

        $this->assertSame( 0, $ary->search(4) );
        $this->assertSame( 0, $ary->search("4") );
        $this->assertSame( 5, $ary->search(3) );
        $this->assertSame( "key", $ary->search("other") );

        $this->assertFalse( $ary->search("not in") );
        $this->assertFalse( $ary->search("OTHER") );
    }

    public function testWithout ()
    {
        $ary = new ::cPHP::Ary(array( 1, 2, 3, "four", "five", "six" ));

        $result = $ary->without( 2 );
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 0 => 1, 2 => 3, 3 => "four", 4 => "five", 5 => "six"),
                $result->get()
            );

        $result = $ary->without( 2, "3" );
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 0 => 1, 3 => "four", 4 => "five", 5 => "six"),
                $result->get()
            );

        $result = $ary->without( 2, "3", "five", array("six") );
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 0 => 1, 3 => "four", 5 => "six"),
                $result->get()
            );
    }

    public function testWithoutKeys ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testSetBranch ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetBranch ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testBranchExists ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testStringize ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testIntegerize ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testCase ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>