<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../general.php";

/**
 * Runs all the tests in the functions folder
 */
class functions_allTests 
{
    
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Functions');
        $suite->addLib();
        $suite->addFromFiles( "functions_", __DIR__, basename(__FILE__) );
        return $suite;
    }

}

?>