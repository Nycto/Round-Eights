<?php
/**
 * Unit Test File
 *
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

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