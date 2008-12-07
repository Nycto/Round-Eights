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
class classes_curry_call extends PHPUnit_Framework_TestCase
{

    // This method exists simply to test the calling of static methods
    static public function staticMethod ()
    {
        return "called";
    }

    public function testCallInternal ()
    {
        $callback = new \cPHP\Curry\Call("trim");
        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
    }

    public function testCallClosure ()
    {
        $callback = new \cPHP\Curry\Call(function ( $value ) {
            return trim($value);
        });

        $this->assertEquals( "trimmed", $callback("  trimmed  ") );
    }

    public function testCallMethod ()
    {
        $hasMethod = $this->getMock('testCall', array('toCall'));

        $hasMethod
            ->expects( $this->once() )
            ->method('toCall')
            ->with( $this->equalTo('argument') )
            ->will( $this->returnValue("called") );

        $callback = new \cPHP\Curry\Call( array($hasMethod, "toCall") );

        $this->assertSame( "called", $callback("argument") );
    }

    public function testCallInvokable ()
    {
        $invokable = $this->getMock('Invokable', array('__invoke'));

        $invokable
            ->expects( $this->once() )
            ->method('__invoke')
            ->with( $this->equalTo('argument') )
            ->will( $this->returnValue("called") );

        $callback = new \cPHP\Curry\Call($invokable);

        $this->assertSame( "called", $callback("argument") );

    }

    public function testCallStatic ()
    {
        $callback = new \cPHP\Curry\Call( array(__CLASS__, "staticMethod") );

        $this->assertEquals( "called", $callback("argument") );
    }

    public function testInstantiateException ()
    {
        $this->setExpectedException('\cPHP\Exception\Argument');
        $callback = new \cPHP\Curry\Call( "ThisIsUnUncallableValue" );
    }

}

?>