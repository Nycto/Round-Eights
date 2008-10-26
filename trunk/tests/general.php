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

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

require_once rtrim( __DIR__, "/" ) ."/../src/commonPHP.php";

error_reporting( E_ALL | E_STRICT );

/**
 * Base unit testing suite class
 *
 * Provides an interface to search and load test suites in a directory
 */
class cPHP_Base_TestSuite extends PHPUnit_Framework_TestSuite
{
    
    /**
     * Recursively collects a list of test files relative to the given base directory
     *
     * @param String $base The base directory to search in
     * @param String $dir A subdirectory of the base to search in
     * @return array
     */
    private function collectFiles ( $base, $dir = FALSE )
    {
        
        $base = rtrim($base, "/" ) ."/";
        
        if ( $dir ) {
            $dir = trim($dir, "/") ."/";
            $search = $base . $dir;
        }
        else {
            $search = $base;
        }
        
        $result = array();
        
        $list = scandir( $search );
        
        foreach ( $list AS $file ) {
            
            if ( substr($file, 0, 1) == "." )
                continue;
            
            if ( is_dir( $search . $file ) )
                $result = array_merge( $result, $this->collectFiles( $base, $dir . $file ) );
                
            else if ( preg_match('/.+\.php$/i', $file) )
                $result[] = $dir . $file;
            
        }
        
        sort( $result );
        
        return $result;
    }
    
    /**
     * Searches a given directory for PHP files and adds the contained tests to the current suite
     *
     * @param String $testPrefix
     * @param String $dir The directory to search in
     * @param String $exclude The file name to exclude from the search
     * @return object Returns a self reference
     */
    public function addFromFiles ( $testPrefix, $dir, $exclude )
    {
        
        $dir = rtrim($dir, "/" ) ."/";
        
        $list = $this->collectFiles($dir);
        
        foreach ( $list AS $file ) {
            
            if ( $file == $exclude )
                continue;
            
            require_once $dir . $file;
            
            $file = str_replace( ".php", "", $file );
            $file = str_replace( "/", "_", $file );
            
            $this->addTestSuite( $testPrefix . $file );
        }
        
        return $this;
    }
    
}

/**
 * Base test suite for a MySQL connection test
 */
class PHPUnit_MySQLi_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    /**
     *
     */
    
    public function setUp ()
    {
        if ( !extension_loaded("mysqli") )
            $this->markTestSkipped("MySQLi extension is not loaded");
        
        $config = rtrim( __DIR__, "/") ."/config.php";
        
        if ( !file_exists($config) )
            $this->markTestSkipped("Config file does not exist: $config");
        
        if ( !is_readable($config) )
            $this->markTestSkipped("Config file is not readable: $config");
        
        require_once $config;
        
        $required = array(
                "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD", "TABLE"
            );
        
        foreach ( $required AS $constant ) {
            
            if ( !defined("MYSQLI_". $constant) )
                $this->markTestSkipped("Required constant is not defined: MYSQLI_". $constant);
            
            $value = constant("MYSQLI_". $constant);
            
            if ( empty($value) )
                $this->markTestSkipped("Required constant must not be empty: MYSQLI_". $constant);
        }
        
        // Test the connection
        $mysqli = new mysqli(
                MYSQLI_HOST,
                MYSQLI_USERNAME,
                MYSQLI_PASSWORD,
                MYSQLI_DATABASE,
                MYSQLI_PORT
            );

        if ($mysqli->connect_error)
            $this->markTestSkipped("MySQLi Connection Error: ".  mysqli_connect_error());
        
    }
    
    public function getURI ()
    {
        return "db://"
            .MYSQLI_USERNAME .":". MYSQLI_PASSWORD ."@"
            .MYSQLI_HOST .":". MYSQLI_PORT
            ."/". MYSQLI_DATABASE;
    }
    
    public function getLink ()
    {
        static $link;
        
        if ( !isset($link) || !$link->isConnected() ) {
            $link = new ::cPHP::DB::MySQLi::Link( $this->getURI() );
            
            
            $mysqli = $link->getLink();
            
            
            $result = $mysqli->query("DROP TEMPORARY TABLE IF EXISTS `". MYSQLI_TABLE ."`");
            
            if ( !$result )
                $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);
                
            
            $result = $mysqli->query("CREATE TEMPORARY TABLE `". MYSQLI_TABLE ."` (
                                  `id` INT NOT NULL auto_increment ,
                               `label` VARCHAR( 255 ) NOT NULL ,
                                `data` VARCHAR( 255 ) NOT NULL ,
                           PRIMARY KEY ( `id` ) ,
                                 INDEX ( `label` ))");
            
            if ( !$result )
                $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);
        
        }
        
        
        $mysqli = $link->getLink();
                
            
            $result = $mysqli->query("TRUNCATE TABLE `". MYSQLI_TABLE ."`");
            
            if ( !$result )
                $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);
        
        $result = $mysqli->query("INSERT INTO `". MYSQLI_TABLE ."`
                             VALUES (1, 'alpha', 'one'),
                                    (2, 'beta', 'two'),
                                    (3, 'gamma', 'three')");
        
        if ( !$result )
            $this->markTestSkipped("MySQLi Error (#". $mysqli->errno ."): ". $mysqli->error);
        
        
        return $link;
    }
    
}

?>