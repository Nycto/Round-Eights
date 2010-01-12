<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Iterator_Cache extends PHPUnit_Framework_TestCase
{
    
    public function testIterate ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );
        
        $iterator = new \r8\Iterator\Debug( new ArrayIterator($data) );

        ob_start();
        PHPUnit_Framework_Constraint_Iterator::assert( $data, $iterator );
        PHPUnit_Framework_Constraint_Iterator::assert( $data, $iterator );
        PHPUnit_Framework_Constraint_Iterator::assert( $data, $iterator );
        $result = ob_end_clean();
        
        $this->assertGreaterThan( 0, strlen($result) );
    }
    
}

?>