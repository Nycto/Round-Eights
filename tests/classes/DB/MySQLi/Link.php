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
class classes_db_mysqli_link extends PHPUnit_MySQLi_Framework_TestCase
{

    public function testConnection_error ()
    {
        $link = new \cPHP\DB\MySQLi\Link(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            );

        try {
            $link->getLink();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Link $err ) {
            $this->assertContains(
                    "Access denied for user",
                    $err->getMessage()
                );
        }
    }

    public function testConnection ()
    {
        $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );
        $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
        $this->assertTrue( $link->isConnected() );
    }

    public function testEscape ()
    {
        $link = $this->getLink();

        // Escape without a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));

        $link->getLink();

        // Escape WITH a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
    }

    public function testQuery_read ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT 50 + 10");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );

        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
    }

    public function testQuery_write ()
    {
        $link = $this->getLink();

        $result = $link->query("UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\Result\Write") );

        $this->assertSame(
                "UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1",
                $result->getQuery()
            );
    }

    public function testDisconnect ()
    {
        $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );
        $link->getLink();

        $this->assertTrue( $link->isConnected() );

        $this->assertSame( $link, $link->disconnect() );

        $this->assertFalse( $link->isConnected() );
    }

}

?>