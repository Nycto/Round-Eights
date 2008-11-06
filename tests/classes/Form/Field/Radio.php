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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_form_field_radio extends PHPUnit_Framework_TestCase
{

    public function testGetRadioOptionID ()
    {
        $field = new ::cPHP::Form::Field::Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        $this->assertSame("radio_fld_356a192b79", $field->getRadioOptionID(1));
        $this->assertSame("radio_fld_da4b9237ba", $field->getRadioOptionID(2));
        $this->assertSame("radio_fld_77de68daec", $field->getRadioOptionID(3));

        try {
            $field->getRadioOptionID(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testGetOptionRadioTag_unchecked ()
    {
        $field = new ::cPHP::Form::Field::Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        $tag = $field->getOptionRadioTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("cPHP::Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fld", $tag['name'] );

        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( 2, $tag['value'] );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "radio", $tag['type'] );

        $this->assertTrue( isset($tag['id']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['id'] );

        $this->assertFalse( isset($tag['checked']) );

        $this->assertNull($tag->getcontent());
    }

    public function testGetOptionRadioTag_checked ()
    {
        $field = new ::cPHP::Form::Field::Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));
        $field->setValue( 2 ) ;

        $tag = $field->getOptionRadioTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("cPHP::Tag") );
        $this->assertSame( "input", $tag->getTag() );

        $this->assertTrue( isset($tag['name']) );
        $this->assertSame( "fld", $tag['name'] );

        $this->assertTrue( isset($tag['value']) );
        $this->assertSame( 2, $tag['value'] );

        $this->assertTrue( isset($tag['type']) );
        $this->assertSame( "radio", $tag['type'] );

        $this->assertTrue( isset($tag['id']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['id'] );

        $this->assertTrue( isset($tag['checked']) );
        $this->assertSame( "checked", $tag['checked'] );

        $this->assertNull($tag->getcontent());
    }

    public function testGetOptionRadioTag_error ()
    {
        $field = new ::cPHP::Form::Field::Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));

        try {
            $field->getOptionRadioTag(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

    public function testGetOptionLabelTag ()
    {
        $field = new ::cPHP::Form::Field::Radio("fld");
        $field->importOptions(array(1 => "One", 2 => "Two", 3 => "Three"));
        $field->setValue( 2 ) ;

        $tag = $field->getOptionLabelTag(2);

        $this->assertThat( $tag, $this->isInstanceOf("cPHP::Tag") );
        $this->assertSame( "label", $tag->getTag() );

        $this->assertTrue( isset($tag['for']) );
        $this->assertSame( "radio_fld_da4b9237ba", $tag['for'] );

        $this->assertSame( "Two", $tag->getcontent() );


        try {
            $field->getOptionLabelTag(4);
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Index $err ) {
            $this->assertSame("Option does not exist in field", $err->getMessage());
        }
    }

}

?>