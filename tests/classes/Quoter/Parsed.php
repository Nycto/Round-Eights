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
class classes_quoter_parsed extends PHPUnit_Framework_TestCase
{

    public function testAddSection ()
    {
        $mock = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $list = new \cPHP\Quoter\Parsed;

        $this->assertSame( $list, $list->addSection($mock) );

        $sections = $list->getSections();

        $this->assertThat( $sections, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array( $mock ), $sections->get() );
    }

    public function testToString ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $list->addSection( new \cPHP\Quoter\Section\Unquoted(0, "snippet") );
        $list->addSection( new \cPHP\Quoter\Section\Quoted(8, "inQuotes", '(', ')') );

        $this->assertSame( "snippet(inQuotes)", $list->__toString() );
        $this->assertSame( "snippet(inQuotes)", "$list" );
    }

    public function testSetIncludeQuoted ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( FALSE ) );

        $this->assertFalse( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( TRUE ) );

        $this->assertTrue( $list->getIncludeQuoted() );
    }

    public function testSetIncludeUnquoted ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( FALSE ) );

        $this->assertFalse( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( TRUE ) );

        $this->assertTrue( $list->getIncludeUnquoted() );
    }

    public function testExplode_all ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "with", "gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "'with", "some'", "gaps"), $result->get() );

        $result = $list->parse( "gg" )->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("", "", ""), $result->get() );
    }

    public function testExplode_noQuoted ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "with", "gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "'with some'", "gaps"), $result->get() );

        $result = $list->parse( "gg" )
            ->setIncludeQuoted( FALSE )
            ->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("", "", ""), $result->get() );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with a few''quoted gaps'"), $result->get() );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with a few'", "'quoted gaps'"), $result->get() );
    }

    public function testExplode_noUnquoted ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String 'with", "some' gaps"), $result->get() );

        $result = $list->parse( "gg" )
            ->setIncludeUnquoted( FALSE )
            ->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("gg"), $result->get() );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with", "a", "few''quoted", "gaps'"), $result->get() );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with", "a", "few' 'quoted", "gaps'"), $result->get() );
    }

    public function testExplode_none ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String 'with some' gaps"), $result->get() );
    }

    public function testFilter ()
    {
        $list = new \cPHP\Quoter;
        $parsed = $list->parse("string 'with' quotes")
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'WITH' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeQuoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'with' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'WITH' quotes", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->setIncludeQuoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'with' quotes", $parsed );
    }
}

?>