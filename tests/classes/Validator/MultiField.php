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
class classes_validator_multifield extends PHPUnit_Framework_TestCase
{

    public function testNonBasics ()
    {
        $field = $this->getMock("cPHP::Form::Multi", array(), array("fld"));
        $valid = new ::cPHP::Validator::MultiField( $field );

        $result = $valid->validate( array() );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );


        $result = $valid->validate( $this->getMock("stub") );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );
    }

    public function testEmptyField ()
    {
        $field = $this->getMock("cPHP::Form::Multi", array(), array("fld"));
        $valid = new ::cPHP::Validator::MultiField( $field );

        $result = $valid->validate( 50 );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );
    }

    public function testInvalidOption ()
    {
        $field = $this->getMock("cPHP::Form::Multi", array(), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $valid = new ::cPHP::Validator::MultiField( $field );

        $result = $valid->validate( 4 );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );

        $result = $valid->validate( "Triple" );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );

        $result = $valid->validate( "ONE" );
        $this->assertFalse( $result->isValid() );
        $this->assertEquals(
                array("Value is not a valid selection"),
                $result->getErrors()->get()
            );
    }

    public function testValid ()
    {
        $field = $this->getMock("cPHP::Form::Multi", array(), array("fld"));
        $field->importOptions(array("one" => "Single", 2 => "Double", "three" => "Triple"));

        $valid = new ::cPHP::Validator::MultiField( $field );

        $this->assertTrue( $valid->isValid( "one" ) );
        $this->assertTrue( $valid->isValid( 2 ) );
        $this->assertTrue( $valid->isValid( "2" ) );
        $this->assertTrue( $valid->isValid( "three" ) );
    }

}

?>