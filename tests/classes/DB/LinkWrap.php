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
class classes_db_linkwrap extends PHPUnit_Framework_TestCase
{

    public function testGetLink ()
    {

        $link = $this->getMock(
                "\cPHP\iface\DB\Link",
                array("query", "quote", "escape")
            );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array(),
                array( $link )
            );

        $this->assertSame( $link, $mock->getLink() );
    }

    public function testQuery ()
    {
        $link = $this->getMock(
                "\cPHP\iface\DB\Link",
                array("query", "quote", "escape")
            );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array(),
                array( $link )
            );

        $link->expects( $this->once() )
            ->method( "query" )
            ->with( "SELECT * FROM table" )
            ->will( $this->returnValue("result") );

        $this->assertSame( "result", $mock->query("SELECT * FROM table") );
    }

    public function testQuote ()
    {
        $link = $this->getMock(
                "\cPHP\iface\DB\Link",
                array("query", "quote", "escape")
            );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array(),
                array( $link )
            );

        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("quoted") );

        $this->assertSame( "quoted", $mock->quote("raw value") );

        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("quoted") );

        $this->assertSame( "quoted", $mock->quote("raw value", FALSE) );
    }

    public function testEscape()
    {
        $link = $this->getMock(
                "\cPHP\iface\DB\Link",
                array("query", "quote", "escape")
            );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array(),
                array( $link )
            );

        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("escaped") );

        $this->assertSame( "escaped", $mock->escape("raw value") );

        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("escaped") );

        $this->assertSame( "escaped", $mock->escape("raw value", FALSE) );
    }

}

?>