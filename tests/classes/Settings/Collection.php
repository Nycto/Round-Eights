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
class classes_Settings_Collection extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test settings collection
     *
     * @return \r8\Settings\Collection
     */
    public function getTestSettings ()
    {
        return new \r8\Settings\Collection(
            new \r8\Settings\Ary(array(
                "stuff" => array( 'one' => 'first', 'two' => 'second' ),
                "trogdor" => array( 'burninate' => 1, 'killable' => 0 ),
            )),
            new \r8\Settings\Ary(array(
                "stuff" => array( 'one' => 1, 'three' => 'third' )
            )),
            new \r8\Settings\Ary(array(
                "stuff" => array( 'two' => 2, 'four' => 'fourth' )
            ))
        );
    }

    public function testGet ()
    {
        $settings = $this->getTestSettings();
        $this->assertSame( 1, $settings->get('stuff', 'one') );
        $this->assertSame( 2, $settings->get('stuff', 'two') );
        $this->assertSame( 'fourth', $settings->get('stuff', 'four') );
        $this->assertSame( 1, $settings->get('trogdor', 'burninate') );
        $this->assertNull( $settings->get('nothing', 'toSeeHere' ) );
    }

}

?>