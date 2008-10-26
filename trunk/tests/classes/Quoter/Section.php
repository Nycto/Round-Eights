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
class classes_quoter_section extends PHPUnit_Framework_TestCase
{
    
    public function testSetContent ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertNull( $section->getContent() );
        
        $this->assertSame( $section, $section->setContent("new string") );
        
        $this->assertEquals( "new string", $section->getContent() );
    }
    
    public function testClearContent ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertNull( $section->getContent() );
        
        $section->setContent("new string");
        
        $this->assertEquals( "new string", $section->getContent() );
        
        $this->assertSame( $section, $section->clearContent() );
        
        $this->assertNull( $section->getContent() );
    }
    
    public function testContentExists ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertFalse( $section->contentExists() );
        
        $section->setContent("new string");
        
        $this->assertTrue( $section->contentExists() );
        
        $section->clearContent();
        
        $this->assertFalse( $section->contentExists() );
        
        $section->setContent("");
        
        $this->assertTrue( $section->contentExists() );
    }
    
    public function testIsEmpty ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(0, null));
        
        $this->assertTrue( $section->isEmpty() );
        
        $section->setContent("");
        $this->assertTrue( $section->isEmpty() );
        
        $section->setContent("  ");
        $this->assertTrue( $section->isEmpty() );
        $this->assertFalse( $section->isEmpty( ALLOW_SPACES ) );
        
        $section->setContent("Some piece of content");
        $this->assertFalse( $section->isEmpty() );
        
        $section->clearContent();
        $this->assertTrue( $section->isEmpty() );
    }
    
    public function testConstruct ()
    {
        $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(10, "data"));
        $this->assertSame( 10, $section->getOffset() );
        $this->assertSame( "data", $section->getContent() );
        
        try {
            $section = $this->getMock("cPHP::Quoter::Section", array("isQuoted", "__toString"), array(-5, "data"));
            $this->fail("An expected exception was not thrown");
        }
        catch ( ::cPHP::Exception::Argument $err ) {
            $this->assertSame( "Must not be less than zero", $err->getMessage() );
        }
    }
    
}

?>