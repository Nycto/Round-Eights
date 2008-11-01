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
 * Provides an interface to create multiple instances even though this is a singleton
 */
class Stub_Env extends ::cPHP::Env
{
    
    static public function fromArray( array $data )
    {
        return new static( $data );
    }
    
}

/**
 * unit tests
 */
class classes_env extends PHPUnit_Framework_TestCase
{
    
    public function testIsLocal ()
    {
        $env = Stub_Env::fromArray(array(
                "SHELL" => "/bin/bash"
            ));
        
        $this->assertTrue( $env->local );
        $this->assertTrue( isset($env->local) );
        
        
        $env = Stub_Env::fromArray(array());
        $this->assertFalse( $env->local );
    }
    
    public function testIP ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_ADDR" => "127.0.0.1"
            ));
        
        $this->assertTrue( isset($env->ip) );
        $this->assertSame( "127.0.0.1", $env->ip );
        
        
        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->ip) );
        $this->assertNull( $env->ip );
    }
    
    public function testQuery ()
    {
        $env = Stub_Env::fromArray(array(
                "QUERY_STRING" => "?var=value"
            ));
        
        $this->assertTrue( isset($env->query) );
        $this->assertSame( "?var=value", $env->query );
        
        
        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->query) );
        $this->assertNull( $env->query );
    }
    
    public function testPort ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_PORT" => "40"
            ));
        
        $this->assertTrue( isset($env->port) );
        $this->assertSame( 40, $env->port );
        
        
        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->port) );
        $this->assertNull( $env->port );
    }
    
    public function testScheme ()
    {
        $env = Stub_Env::fromArray(array(
                "SERVER_PROTOCOL" => "HTTP/1.1"
            ));
        
        $this->assertTrue( isset($env->scheme) );
        $this->assertSame( "http", $env->scheme );
        
        
        $env = Stub_Env::fromArray(array());
        $this->assertFalse( isset($env->scheme) );
        $this->assertNull( $env->scheme );
    }
    
}

?>