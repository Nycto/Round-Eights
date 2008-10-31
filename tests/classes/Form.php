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
 * unit tests
 */
class classes_form extends PHPUnit_Framework_TestCase
{
    
    public function getMockField ()
    {
        return $this->getMock(
                "cPHP::iface::Form::Field",
                array("getName", "getValue", "setValue")
            );
    }
    
    public function testGetAddField ()
    {
        $form = new ::cPHP::Form;
        
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array(), $fields->get() );
        
        
        // Add a field
        $field1 = $this->getMockField();
        $this->assertSame( $form, $form->addField($field1) );
        
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array($field1), $fields->get() );
        
        
        // Make sure duplicates aren't allowed
        $this->assertSame( $form, $form->addField($field1) );
        
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array($field1), $fields->get() );
        
        
        // Add another field
        $field2 = $this->getMockField();
        $this->assertSame( $form, $form->addField($field2) );
        
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array($field1, $field2), $fields->get() );
    }
    
    public function testClearFields ()
    {
        $form = new ::cPHP::Form;

        $field1 = $this->getMockField();
        $form->addField($field1);
        
        $field2 = $this->getMockField();
        $form->addField($field2);
        
        // Make sure the two fields were properly added
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array($field1, $field2), $fields->get() );
        
        
        $this->assertSame( $form, $form->clearFields() );
        
        $fields = $form->getFields();
        $this->assertThat( $fields, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame( array(), $fields->get() );
    }
    
    public function testCount ()
    {
        $form = new ::cPHP::Form;
        $this->assertSame( 0, $form->count() );
        $this->assertSame( 0, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 1, $form->count() );
        $this->assertSame( 1, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 2, $form->count() );
        $this->assertSame( 2, count($form) );

        $form->addField($this->getMockField());
        $this->assertSame( 3, $form->count() );
        $this->assertSame( 3, count($form) );
        
        $form->clearFields();
        $this->assertSame( 0, $form->count() );
        $this->assertSame( 0, count($form) );
    }
    
    public function testFind ()
    {
        $field1 = $this->getMockField();
        $field1->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldOne") );
        
        $field2 = $this->getMockField();
        $field2->expects( $this->any() )
            ->method("getName")
            ->will( $this->returnValue("fldTwo") );
        
        $form = new ::cPHP::Form;
        $form->addField( $field1 )->addField( $field2 );
        
        $this->assertSame( $field1, $form->find("fldOne") );
        
        $this->markTestIncomplete("Test exceptions");
        $this->markTestIncomplete("Test field not found");
    }
    
}

?>