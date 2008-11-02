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
class classes_form_field extends PHPUnit_Framework_TestCase
{

    public function testSetGetName ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $this->assertSame("fld", $field->getName());

        $this->assertSame( $field, $field->setName("fieldName") );
        $this->assertSame("fieldName", $field->getName());

        try {
            $field->setName("123");
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must be a valid PHP variable name", $err->getMessage() );
        }
    }

    public function testGetFilter ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $filter = $field->getFilter();

        $this->assertThat( $filter, $this->isInstanceOf("cPHP::Filter::Chain") );

        $this->assertSame( $filter, $field->getFilter() );
    }

    public function testSetFilter ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $filter = $this->getMock("cPHP::iface::Filter", array("filter"));

        $this->assertSame( $field, $field->setFilter($filter) );

        $this->assertSame( $filter, $field->getFilter() );
    }

    public function testGetValidator ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $validator = $field->getValidator();

        $this->assertThat( $validator, $this->isInstanceOf("cPHP::Validator::Collection::Any") );

        $this->assertSame( $validator, $field->getValidator() );
    }

    public function testSetValidator ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $validator = $this->getMock("cPHP::iface::Validator", array("validate", "isValid"));

        $this->assertSame( $field, $field->setValidator($validator) );

        $this->assertSame( $validator, $field->getValidator() );
    }

    public function testSetValue ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $this->assertNull( $field->getRawValue() );

        $this->assertSame( $field, $field->setValue("New Value") );
        $this->assertSame( "New Value", $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( array(505) ) );
        $this->assertSame( 505, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( TRUE ) );
        $this->assertSame( TRUE, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( NULL ) );
        $this->assertSame( NULL, $field->getRawValue() );

        $this->assertSame( $field, $field->setValue( 0.22 ) );
        $this->assertSame( 0.22, $field->getRawValue() );
    }

    public function testGetValue ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));

        $field->setValue("New Value");

        $this->assertSame("New Value", $field->getValue());

        $field->setFilter( new ::cPHP::Curry::Call("strtoupper") );

        $this->assertSame("NEW VALUE", $field->getValue());
        $this->assertSame("New Value", $field->getRawValue());
    }

    public function testValidate ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));
        $field->setValidator( new ::cPHP::Validator::NoSpaces );

        $field->setValue("Some String 123");
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertFalse( $result->isValid() );

        $field->setValue("SomeString123");
        $result = $field->validate();
        $this->assertThat( $result, $this->isInstanceOf("cPHP::Validator::Result") );
        $this->assertTrue( $result->isValid() );
    }

    public function testIsValid ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));
        $field->setValidator( new ::cPHP::Validator::NoSpaces );

        $field->setValue("Some String 123");
        $this->assertFalse( $field->isValid() );

        $field->setValue("SomeString123");
        $this->assertTrue( $field->isValid() );
    }

    public function testGetTag ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));
        $field->setValue("New Value")
            ->setName("fldName");

        $tag = $field->getTag();

        $this->assertThat( $tag, $this->isInstanceOf("cPHP::Tag") );
        $this->assertSame( "input", $tag->getTag() );
        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fldName", $tag['name'] );
        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( "New Value", $tag['value'] );
    }

    public function testToString ()
    {
        $field = $this->getMock("cPHP::Form::Field", array(), array("fld"));
        $field->setValue("New Value")
            ->setName("fldName");

        $this->assertSame(
                '<input value="New Value" name="fldName" />',
                $field->__toString()
            );

        $this->assertSame(
                '<input value="New Value" name="fldName" />',
                "$field"
            );
    }

}

?>