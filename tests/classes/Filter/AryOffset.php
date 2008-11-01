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
class classes_filter_aryoffset extends PHPUnit_Framework_TestCase
{

    public function testSetFilter ()
    {
        $filter = new ::cPHP::Filter::AryOffset;

        $intFilter = new ::cPHP::Filter::Integer;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $intFilter )
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $intFilter ),
                $list->get()
            );


        $boolFilter = new ::cPHP::Filter::Boolean;
        $this->assertEquals(
                $filter,
                $filter->setFilter( 50, $boolFilter )
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $boolFilter ),
                $list->get()
            );


        $this->assertEquals(
                $filter,
                $filter->setFilter( "str", $intFilter)
            );

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $this->assertSame(
                array( 50 => $boolFilter, "str" => $intFilter ),
                $list->get()
            );
    }

    public function testImport ()
    {
        $filter = new ::cPHP::Filter::AryOffset;

        $filter->import(array(
                5 => new ::cPHP::Filter::Number,
                "index" => new ::cPHP::Filter::URL
            ));

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $list = $list->get();

        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP::Filter::Number") );

        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP::Filter::URL") );
    }

    public function testConstruct ()
    {
        $filter = new ::cPHP::Filter::AryOffset(array(
                5 => new ::cPHP::Filter::Number,
                "index" => new ::cPHP::Filter::URL
            ));

        $list = $filter->getFilters();
        $this->assertThat( $list, $this->isInstanceOf("cPHP::Ary") );
        $list = $list->get();

        $this->assertArrayHasKey( 5, $list );
        $this->assertThat( $list[5], $this->isInstanceOf("cPHP::Filter::Number") );

        $this->assertArrayHasKey( "index", $list );
        $this->assertThat( $list["index"], $this->isInstanceOf("cPHP::Filter::URL") );
    }

    public function testFilter ()
    {
        $filter = new ::cPHP::Filter::AryOffset(array(
                1 => new ::cPHP::Filter::Number,
                5 => new ::cPHP::Filter::Boolean
            ));

        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $filter->filter(array( 1 => "10", 5 => 1 ))
            );

        $ary = new ::cPHP::Ary(array( 1 => "10", 5 => 1 ));
        $result = $filter->filter( $ary );

        $this->assertSame( $ary, $result );
        $this->assertSame(
                array( 1 => 10, 5 => true ),
                $result->get()
            );

    }

}

?>