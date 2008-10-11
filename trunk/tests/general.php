<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

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
    
    /**
     * If needed, this will add a test to include the cPHP library and the tests associated with including it
     *
     * @return object Returns a self reference
     */
    public function addLib ()
    {
        static $included;
        
        if ( !isset($included) || !$included ) {
            $included = TRUE;
            $this->addTestSuite( 'general' );
        }
        
        return $this;
    }
    
}

/**
 * Base test suite for a MySQL connection test
 */
class PHPUnit_MySQL_Framework_TestCase extends PHPUnit_Framework_TestCase
{
    
    public function setUp ()
    {
        if ( !extension_loaded("mysqli") )
            $this->markTestSkipped("MySQLi extension is not loaded");
        
        $config = rtrim( dirname( __FILE__ ), "/") ."/config.php";
        
        if ( !file_exists($config) )
            $this->markTestSkipped("Config file does not exist: $config");
        
        if ( !is_readable($config) )
            $this->markTestSkipped("Config file is not readable: $config");
        
        require_once $config;
        
        $required = array(
                "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD"
            );
        
        foreach ( $required AS $constant ) {
            
            if ( !defined("MYSQL_". $constant) )
                $this->markTestSkipped("Required constant is not defined: MYSQL_". $constant);
            
            $value = constant("MYSQL_". $constant);
            
            if ( empty($value) )
                $this->markTestSkipped("Required constant must not be empty: MYSQL_". $constant);
        }
        
    }
    
    
    
}

/**
 * unit tests
 */
class general extends PHPUnit_Extensions_OutputTestCase
{
    
    public function testLibInclude ()
    {
        
        // Ensures that the library doesn't output anything when it is included
        $this->expectOutputString('');
        require_once rtrim( dirname( __FILE__ ), "/" ) ."/../src/commonPHP.php";
        
        // Ensure that we cleaned up any global variables
        $this->assertEquals( array(), get_defined_vars() );
        
    }
    
}

?>