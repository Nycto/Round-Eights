<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/general.php";
require_once rtrim( __DIR__, "/" ) ."/functions/allTests.php";
require_once rtrim( __DIR__, "/" ) ."/classes/allTests.php";

/**
 * Unit test for running all the tests
 */
class AllTests
{

    public static function suite()
    {
        $suite = new h2o_Base_TestSuite('All RaindropPHP Tests');
        $suite->addTestSuite( 'functions_allTests' );
        $suite->addTestSuite( 'classes_allTests' );
        return $suite;
    }

}

?>