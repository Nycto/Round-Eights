<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * Runs all the tests in the classes folder
 */
class classes_allTests
{
    
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('commonPHP Classes');
        
        $suite->addTestSuite( 'general' );

        // Now we automaticall find all tests in the current directory, include their files, and add them to this suite
        $dir = rtrim( dirname( __FILE__ ), "/" ) ."/";
        $tests = glob($dir . "*.php");
        foreach( $tests AS $file ) {
            
            $base = basename( $file );
            
            if ( $base == basename( __FILE__ ) )
                continue;
            
            require_once $file;
            
            $base = str_replace( ".php", "", $base );
            
            $suite->addTestSuite('classes_'. $base);
        }
        
        return $suite;
    }

}
?>