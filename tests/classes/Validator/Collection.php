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
class classes_validator_collection extends PHPUnit_Framework_TestCase
{

    public function testAddObject ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));

        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid ), $list->get());
    }

    public function testAddObjectError ()
    {
        $this->setExpectedException("cPHP::Exception::Argument");

        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = $this->getMock("stub_random_class");

        $collection->add($valid);
    }

    public function testAddInterfaceString ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));

        $valid = get_class( $this->getMock("cPHP::iface::Validator", array("validate", "isValid")) );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }

    public function testAddClassString ()
    {
        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));

        $valid = get_class( $collection );

        $this->assertSame( $collection, $collection->add($valid) );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertThat( $list->offsetGet(0), $this->isInstanceOf( $valid ) );
    }

    public function testAddStringError ()
    {
        $this->setExpectedException("cPHP::Exception::Argument");

        $collection = $this->getMock("cPHP::Validator::Collection", array("process"));
        $valid = get_class( $this->getMock("stub_random_class") );

        $collection->add($valid);
    }

    public function testAddMany ()
    {
        $collection = $this->getMock( "cPHP::Validator::Collection", array("process") );

        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));

        $this->assertSame(
                $collection,
                $collection->addMany( array( $valid, "Non validator" ), array(), $valid2 )
            );

        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());

    }

    public function testConstruct ()
    {

        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));

        $collection = $this->getMock(
                "cPHP::Validator::Collection",
                array("process"),
                array( $valid, "Not a validator", $valid2 )
            );


        $list = $collection->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());

    }

    public function testCallStatic ()
    {
        $valid = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));
        $valid2 = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));

        $validator = cPHP::Validator::Collection::All();
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::All") );


        $validator = cPHP::Validator::Collection::All( $valid );
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::All") );

        $list = $validator->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid ), $list->get());


        $validator = cPHP::Validator::Collection::Any( $valid, $valid2 );
        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::Any") );

        $list = $validator->getValidators();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertEquals(array( $valid, $valid2 ), $list->get());
    }

}

?>