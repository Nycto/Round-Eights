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
class classes_form_multi extends PHPUnit_Framework_TestCase
{

    public function testAddOption_strValue ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption("str", "lbl") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("str" => "lbl"), $opts->get() );

    }

    public function testAddOption_intValue ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(50, "lbl") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array(50 => "lbl"), $opts->get() );

    }

    public function testAddOption_floatValue ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(1.5, "othr") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => "othr"),
                $opts->get()
            );
    }

    public function testAddOption_boolValue ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(FALSE, "lbl") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(0 => "lbl"),
                $opts->get()
            );

        $this->assertSame( $mock, $mock->addOption(TRUE, "lbl2") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(0 => "lbl", 1 => "lbl2"),
                $opts->get()
            );
    }

    public function testAddOption_nullValue ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption(null, "lbl") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array("" => "lbl"),
                $opts->get()
            );

    }

    public function testAddOption_objValue ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( $this->getMock("stub"), "lbl") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array("" => "lbl"),
                $opts->get()
            );

    }

    public function testAddOption_nonStringLabel ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( 1, 5) );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => "5"),
                $opts->get()
            );

        $this->assertSame( $mock, $mock->addOption( 2, 27.8) );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => "5", 2 => "27.8"),
                $opts->get()
            );

    }

    public function testAddOption_conflict ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame( $mock, $mock->addOption( "val", "one") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array("val" => "one"),
                $opts->get()
            );


        $this->assertSame( $mock, $mock->addOption( "val", "two") );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array("val" => "two"),
                $opts->get()
            );

    }

    public function testHasOption ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $this->assertTrue( $mock->hasOption(1) );
        $this->assertTrue( $mock->hasOption(2) );
        $this->assertTrue( $mock->hasOption(3) );

        $this->assertFalse( $mock->hasOption(4) );
    }

    public function testGetOptionLabel ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $this->assertSame( "one", $mock->getOptionLabel(1) );
        $this->assertSame( "two", $mock->getOptionLabel(2) );
        $this->assertSame( "three", $mock->getOptionLabel(3) );

        $this->assertSame( "one", $mock->getOptionLabel("1") );
        $this->assertSame( "two", $mock->getOptionLabel("2") );
        $this->assertSame( "three", $mock->getOptionLabel("3") );

        try {
            $mock->getOptionLabel(4);
            $this->fail("An expected exception was not thrown");
        }
        catch( \cPHP\Exception\Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testRemoveOption ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $opts->get()
            );

        $this->assertSame( $mock, $mock->removeOption(2) );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => 'one', 3 => 'three'),
                $opts->get()
            );
    }

    public function testClearOptions ()
    {

        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));
        $mock->addOption( 1, "one");
        $mock->addOption( 2, "two");
        $mock->addOption( 3, "three");

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $opts->get()
            );

        $this->assertSame( $mock, $mock->clearOptions() );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array(), $opts->get() );

    }

    public function testImportOptions ()
    {
        $mock = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));

        $this->assertSame(
                $mock,
                $mock->importOptions(array(1 => 'one', 2 => 'two', 3 => 'three'))
            );

        $opts = $mock->getOptions();
        $this->assertThat( $opts, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                $opts->get()
            );

    }

    public function testDefaultValidator ()
    {
        $field = $this->getMock("cPHP\Form\Multi", array("_mock"), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $this->assertThat(
                $field->getValidator(),
                $this->isInstanceOf("cPHP\Validator\MultiField")
            );

        $field->setValue( "one" );
        $this->assertTrue( $field->isValid() );

        $field->setValue( 5 );
        $this->assertFalse( $field->isValid() );
    }

}

?>