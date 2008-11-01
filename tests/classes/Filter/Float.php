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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filter_float extends PHPUnit_Framework_TestCase
{

    public function testInteger ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(1) );
        $this->assertSame( 20.0, $filter->filter(20) );
        $this->assertSame( -10.0, $filter->filter(-10) );
        $this->assertSame( 0.0, $filter->filter(0) );
    }

    public function testBoolean ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(TRUE) );
        $this->assertSame( 0.0, $filter->filter(FALSE) );
    }

    public function testFloat ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 1.0, $filter->filter(1.0) );
        $this->assertSame( .5, $filter->filter(.5) );
        $this->assertSame( 20.25, $filter->filter(20.25) );
        $this->assertSame( -10.75, $filter->filter(-10.75) );
        $this->assertSame( 0.0, $filter->filter(0.0) );
    }

    public function testNull ()
    {
        $filter = new cPHP::Filter::Float;
        $this->assertSame( 0.0, $filter->filter(NULL) );
    }

    public function testIntegerString ()
    {
        $filter = new cPHP::Filter::Float;

        $this->assertSame( 0.0, $filter->filter("Some String") );
        $this->assertSame( 20.0, $filter->filter("20") );
        $this->assertSame( -20.0, $filter->filter("-20") );
        $this->assertSame( -40.0, $filter->filter("- 40") );
        $this->assertSame( 404040.0, $filter->filter("40-40-40") );
        $this->assertSame( -402030.0, $filter->filter("-40-20-30") );
        $this->assertSame( 50.0, $filter->filter("Some50String") );

    }

    public function testFloatString ()
    {
        $filter = new cPHP::Filter::Float;

        $this->assertSame( 20.0, $filter->filter("20.0") );
        $this->assertSame( -20.04, $filter->filter("-20.04") );
        $this->assertSame( -40.90, $filter->filter("- 40.90d") );
        $this->assertSame( 50.123, $filter->filter("Some50.123String") );
        $this->assertSame( 50.12, $filter->filter("Some50.12.3String") );
    }

    public function testArray ()
    {
        $filter = new cPHP::Filter::Float;

        $this->assertSame( 50.5, $filter->filter( array(50.5) ) );
        $this->assertSame( 0.0, $filter->filter( array() ) );
    }

    public function testObject ()
    {
        $filter = new cPHP::Filter::Float;

        $this->assertSame( 1.0, $filter->filter( $this->getMock("stub_random_obj") ) );
    }

}

?>