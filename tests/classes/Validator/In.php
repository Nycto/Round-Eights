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
class classes_validator_in extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $valid = new ::cPHP::Validator::In(array("one", "two", "three"));

        $list = $valid->getList();

        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "two", "three"),
                $list->get()
            );


        try {
            new ::cPHP::Validator::In("invalid");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must be an array or a traversable object", $err->getMessage() );
        }
    }

    public function testSetList ()
    {
        $valid = new ::cPHP::Validator::In;

        $this->assertSame( $valid, $valid->setList(array("one", "two", "three")) );

        $list = $valid->getList();

        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "two", "three"),
                $list->get()
            );


        try {
            $valid->setList("Invalid");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must be an array or a traversable object", $err->getMessage() );
        }
    }

    public function testSetList_unique ()
    {
        $valid = new ::cPHP::Validator::In;
        $valid->setList(array("one", "two", "three", "Three", "two"));

        $list = $valid->getList();

        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "two", "three", "Three"),
                $list->get()
            );
    }

    public function testAdd ()
    {
        $valid = new ::cPHP::Validator::In;

        $this->assertSame( $valid, $valid->add("one") );

        $list = $valid->getList();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array("one"), $list->get() );


        $this->assertSame( $valid, $valid->add("two") );

        $list = $valid->getList();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array("one", "two"), $list->get() );
    }

    public function testExists ()
    {
        $valid = new ::cPHP::Validator::In(array("one", "two", "three"));

        $this->assertTrue( $valid->exists("one") );
        $this->assertFalse( $valid->exists("four") );
    }

    public function testRemove ()
    {
        $valid = new ::cPHP::Validator::In(array("one", "two", "three", "four"));

        $this->assertSame( $valid, $valid->remove("two") );

        $list = $valid->getList();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "three", "four"),
                $list->get()
            );


        $this->assertSame( $valid, $valid->remove("five") );

        $list = $valid->getList();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "three", "four"),
                $list->get()
            );


        $this->assertSame( $valid, $valid->remove("FOUR") );

        $list = $valid->getList();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array("one", "three", "four"),
                $list->get()
            );
    }

    public function testValid ()
    {
        $valid = new ::cPHP::Validator::In(array(1, 1.5, "two", TRUE, FALSE, NULL));

        $this->assertTrue( $valid->isValid(1) );
        $this->assertTrue( $valid->isValid("1") );
        $this->assertTrue( $valid->isValid(1.5) );
        $this->assertTrue( $valid->isValid("two") );
        $this->assertTrue( $valid->isValid(TRUE) );
        $this->assertTrue( $valid->isValid(FALSE) );
        $this->assertTrue( $valid->isValid(NULL) );
    }

    public function testInalid ()
    {
        $valid = new ::cPHP::Validator::In(array(1, 1.5, "two", TRUE, FALSE, NULL));

        $result = $valid->validate("123");
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Invalid option"),
                $result->getErrors()->get()
            );
    }

}

?>