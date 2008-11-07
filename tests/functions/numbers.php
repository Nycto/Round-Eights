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
 * numeric function unit tests
 */
class functions_numbers extends PHPUnit_Framework_TestCase
{

    function testPositive ()
    {
        $this->assertTrue( cPHP::num::positive(1) );
        $this->assertTrue( cPHP::num::positive(.1) );

        $this->assertFalse( cPHP::num::positive(-1) );
        $this->assertFalse( cPHP::num::positive(-.1) );

        $this->assertFalse( cPHP::num::positive(0) );
    }

    function testNegative ()
    {
        $this->assertFalse( cPHP::num::negative(1) );
        $this->assertFalse( cPHP::num::negative(.1) );

        $this->assertTrue( cPHP::num::negative(-1) );
        $this->assertTrue( cPHP::num::negative(-.1) );

        $this->assertFalse( cPHP::num::negative(0) );
    }

    function testNegate ()
    {
        $this->assertEquals( -1, cPHP::num::negate(1) );
        $this->assertEquals( -1.5, cPHP::num::negate(1.5) );
        $this->assertEquals( -10000000, cPHP::num::negate(10000000) );
        $this->assertEquals( -10000000.5, cPHP::num::negate(10000000.5) );

        $this->assertEquals( 1, cPHP::num::negate(-1) );
        $this->assertEquals( 1.5, cPHP::num::negate(-1.5) );
        $this->assertEquals( 10000000, cPHP::num::negate(-10000000) );
        $this->assertEquals( 10000000.5, cPHP::num::negate(-10000000.5) );

        $this->assertEquals( 0, cPHP::num::negate(0) );
    }

    function testBetween ()
    {
        $this->assertTrue( cPHP::num::between( 8, 4, 10 ) );
        $this->assertTrue( cPHP::num::between( 8, 4.5, 10.5 ) );
        $this->assertTrue( cPHP::num::between( 8.5, 4, 10.5 ) );

        $this->assertFalse( cPHP::num::between( 2, 4, 10 ) );
        $this->assertFalse( cPHP::num::between( 2, 4, 10.5 ) );
        $this->assertFalse( cPHP::num::between( 2.5, 4, 10 ) );

        $this->assertFalse( cPHP::num::between( 12, 4, 10 ) );
        $this->assertFalse( cPHP::num::between( 12.5, 4.5, 10 ) );
        $this->assertFalse( cPHP::num::between( 12, 4.5, 10 ) );

        $this->assertTrue( cPHP::num::between( 10, 4, 10 ) );
        $this->assertTrue( cPHP::num::between( 4, 4, 10 ) );
        $this->assertFalse( cPHP::num::between( 10, 4, 10, FALSE ) );
        $this->assertFalse( cPHP::num::between( 10, 4, 10, FALSE ) );

        $this->assertTrue( cPHP::num::between( 10.5, 4.5, 10.5 ) );
        $this->assertTrue( cPHP::num::between( 4.5, 4.5, 10.5 ) );
        $this->assertFalse( cPHP::num::between( 10.5, 4.5, 10.5, FALSE ) );
        $this->assertFalse( cPHP::num::between( 10.5, 4.5, 10.5, FALSE ) );
    }

    function testLimit ()
    {
        $this->assertEquals( 8, cPHP::num::limit(8, 4, 10) );
        $this->assertEquals( 4, cPHP::num::limit(2, 4, 10) );
        $this->assertEquals( 10, cPHP::num::limit(12, 4, 10) );

        $this->assertEquals( 8.5, cPHP::num::limit(8.5, 4.5, 10.5) );
        $this->assertEquals( 4.5, cPHP::num::limit(2, 4.5, 10.5) );
        $this->assertEquals( 10.5, cPHP::num::limit(12, 4.5, 10.5) );
    }

    function testIntWrap ()
    {
        $this->assertEquals( 15, cPHP::num::intWrap( 37, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::intWrap( 26, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::intWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::intWrap( 4, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::intWrap( -7, 10, 20 ) );

        $this->assertEquals( 10, cPHP::num::intWrap( -1, 10, 20 ) );
        $this->assertEquals( 10, cPHP::num::intWrap( 10, 10, 20 ) );
        $this->assertEquals( 20, cPHP::num::intWrap( 20, 10, 20 ) );
        $this->assertEquals( 20, cPHP::num::intWrap( 31, 10, 20 ) );
    }

    function testNumWrap ()
    {
        $this->assertEquals( 15, cPHP::num::numWrap( 35, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::numWrap( 25, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::numWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::numWrap( 5, 10, 20 ) );
        $this->assertEquals( 15, cPHP::num::numWrap( -5, 10, 20 ) );

        $this->assertEquals( 10, cPHP::num::numWrap( 10, 10, 20 ) );
        $this->assertEquals( 10, cPHP::num::numWrap( 20, 10, 20 ) );

        $this->assertEquals( 20, cPHP::num::numWrap( 10, 10, 20, FALSE ) );
        $this->assertEquals( 20, cPHP::num::numWrap( 20, 10, 20, FALSE ) );
    }

    function testOffsetWrap ()
    {
        try {
            ::cPHP::num::offsetWrap(5, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Invalid offset wrap flag", $err->getMessage() );
        }

        try {
            ::cPHP::num::offsetWrap(0, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must be greater than zero", $err->getMessage() );
        }

        $this->assertEquals(0, cPHP::num::offsetWrap(5, -5, cPHP::num::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, -2, cPHP::num::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, -1, cPHP::num::OFFSET_NONE) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, 0, cPHP::num::OFFSET_NONE) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, 3, cPHP::num::OFFSET_NONE) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 4, cPHP::num::OFFSET_NONE) );

        try {
            ::cPHP::num::offsetWrap(1, 2, cPHP::num::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            cPHP::num::offsetWrap(5, 5, cPHP::num::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            cPHP::num::offsetWrap(5, -6, cPHP::num::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, cPHP::num::offsetWrap(5, -14, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(2, cPHP::num::offsetWrap(5, -8, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, -5, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, -2, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, -1, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, 0, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, 3, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 4, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, 8, cPHP::num::OFFSET_WRAP) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, 15, cPHP::num::OFFSET_WRAP) );

        $this->assertEquals(0, cPHP::num::offsetWrap(5, -14, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, -8, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, -5, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, -2, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, -1, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, 0, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, 3, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 4, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 8, cPHP::num::OFFSET_RESTRICT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 15, cPHP::num::OFFSET_RESTRICT) );

        $this->assertEquals(0, cPHP::num::offsetWrap(5, -2, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, -1, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(0, cPHP::num::offsetWrap(5, 0, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(3, cPHP::num::offsetWrap(5, 3, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 4, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 8, cPHP::num::OFFSET_LIMIT) );
        $this->assertEquals(4, cPHP::num::offsetWrap(5, 15, cPHP::num::OFFSET_LIMIT) );

    }

}

?>