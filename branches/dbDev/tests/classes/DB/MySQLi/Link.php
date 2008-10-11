<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once rtrim( dirname( __FILE__ ), "/" ) ."/../../../general.php";

/**
 * test suite
 */
class classes_db_mysqli_link
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP MySQLi Connection Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_mysqli_link_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_mysqli_link_tests extends PHPUnit_MySQLi_Framework_TestCase
{
    
    public function testConnection ()
    {
        
    }
    
}

?>