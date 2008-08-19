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
    // Ensures that the library doesn't output anything when it is included
    public function testIncludeOutput ()
    {
        $this->expectOutputString('');
        require_once rtrim( dirname( __FILE__ ), "/" ) ."/../lib/commonPHP.php";
    }
}

?>