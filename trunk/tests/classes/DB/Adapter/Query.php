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
class classes_db_adapter_query
{
    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite('commonPHP Database Query Adapter Class');
        $suite->addLib();
        $suite->addTestSuite( 'classes_db_adapter_query_tests' );
        return $suite;
    }
}

/**
 * unit tests
 */
class classes_db_adapter_query_tests extends PHPUnit_Framework_TestCase
{
    
    public function testGetFieldList ()
    {
        $this->markTestIncomplete("To be deleted");
    }

    
}

?>