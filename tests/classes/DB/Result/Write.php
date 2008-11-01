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
class classes_db_result_write extends PHPUnit_Framework_TestCase
{

    public function testGetAffected ()
    {
        $write = new ::cPHP::DB::Result::Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 5, $write->getAffected() );


        $write = new ::cPHP::DB::Result::Write(
                null,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 0, $write->getAffected() );


        $write = new ::cPHP::DB::Result::Write(
                -5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertSame( 0, $write->getAffected() );
    }

    public function testGetInsertID ()
    {
        $write = new ::cPHP::DB::Result::Write(
                5,
                null,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertNull( $write->getInsertID() );


        $write = new ::cPHP::DB::Result::Write(
                5,
                FALSE,
                "UPDATE table SET field = 'new' LIMIT 5"
            );

        $this->assertNull( $write->getInsertID() );


        $write = new ::cPHP::DB::Result::Write(
                1,
                50,
                "INSERT INTO table SET field = 'new'"
            );

        $this->assertSame( 50, $write->getInsertID() );


        $write = new ::cPHP::DB::Result::Write(
                1,
                -10,
                "INSERT INTO table SET field = 'new'"
            );

        $this->assertNull( $write->getInsertID() );
    }

}

?>