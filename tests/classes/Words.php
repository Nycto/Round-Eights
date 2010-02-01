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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Words extends PHPUnit_Framework_TestCase
{

    public function testPluralize ()
    {
        $this->assertEquals( "tests", \r8\Words::pluralize("test") );
        $this->assertEquals( "tries", \r8\Words::pluralize("try") );
        $this->assertEquals( "quizes", \r8\Words::pluralize("quiz") );
        $this->assertEquals( "apexes", \r8\Words::pluralize("apex") );
        $this->assertEquals( "houses", \r8\Words::pluralize("house") );
        $this->assertEquals( "mice", \r8\Words::pluralize("mouse") );
        $this->assertEquals( "lice", \r8\Words::pluralize("louse") );
        $this->assertEquals( "indices", \r8\Words::pluralize("index") );
        $this->assertEquals( "matrices", \r8\Words::pluralize("matrix") );
        $this->assertEquals( "vertices", \r8\Words::pluralize("vertex") );
        $this->assertEquals( "boxes", \r8\Words::pluralize("box") );
        $this->assertEquals( "fetches", \r8\Words::pluralize("fetch") );
        $this->assertEquals( "passes", \r8\Words::pluralize("pass") );
        $this->assertEquals( "pushes", \r8\Words::pluralize("push") );
        $this->assertEquals( "pays", \r8\Words::pluralize("pay") );
        $this->assertEquals( "abbeys", \r8\Words::pluralize("abbey") );
        $this->assertEquals( "toys", \r8\Words::pluralize("toy") );
        $this->assertEquals( "buys", \r8\Words::pluralize("buy") );
        $this->assertEquals( "soliloquies", \r8\Words::pluralize("soliloquy") );
        $this->assertEquals( "hives", \r8\Words::pluralize("hive") );
        $this->assertEquals( "scarves", \r8\Words::pluralize("scarf") );
        $this->assertEquals( "calves", \r8\Words::pluralize("calf") );
        $this->assertEquals( "carafes", \r8\Words::pluralize("carafe") );
        $this->assertEquals( "coiffes", \r8\Words::pluralize("coiffe") );
        $this->assertEquals( "synapses", \r8\Words::pluralize("synapsis") );
        $this->assertEquals( "atria", \r8\Words::pluralize("atrium") );
        $this->assertEquals( "buffaloes", \r8\Words::pluralize("buffalo") );
        $this->assertEquals( "tomatoes", \r8\Words::pluralize("tomato") );
        $this->assertEquals( "buses", \r8\Words::pluralize("bus") );
        $this->assertEquals( "statuses", \r8\Words::pluralize("status") );
        $this->assertEquals( "aliases", \r8\Words::pluralize("alias") );
        $this->assertEquals( "viri", \r8\Words::pluralize("virus") );
        $this->assertEquals( "octopi", \r8\Words::pluralize("octopus") );
        $this->assertEquals( "axes", \r8\Words::pluralize("axis") );
        $this->assertEquals( "testes", \r8\Words::pluralize("testis") );
        $this->assertEquals( "puffs", \r8\Words::pluralize("puff") );

        $this->assertEquals( "oxen", \r8\Words::pluralize("ox") );
        $this->assertEquals( "staves", \r8\Words::pluralize("staff") );
        $this->assertEquals( "people", \r8\Words::pluralize("person") );
        $this->assertEquals( "men", \r8\Words::pluralize("man") );
        $this->assertEquals( "children", \r8\Words::pluralize("child") );
        $this->assertEquals( "sexes", \r8\Words::pluralize("sex") );
        $this->assertEquals( "moves", \r8\Words::pluralize("move") );

        $this->assertEquals( "deer", \r8\Words::pluralize("deer") );
        $this->assertEquals( "moose", \r8\Words::pluralize("moose") );
        $this->assertEquals( "sheep", \r8\Words::pluralize("sheep") );

        // Test when there is padding
        $this->assertEquals( "tries", \r8\Words::pluralize("   try   ") );

        // Test the case
        $this->assertEquals( "TESTS", \r8\Words::pluralize("TEST") );

        // Test when there is only one
        $this->assertEquals( "test", \r8\Words::pluralize("test", 1) );

        // Test when there are multiple
        $this->assertEquals( "tests", \r8\Words::pluralize("test", 5) );

        // Test a blank string
        $this->assertEquals( "", \r8\Words::pluralize("   ") );

        // Test the capitalization rules for irregular plurals
        $this->assertEquals( "PEOPLE", \r8\Words::pluralize("PERSON") );
        $this->assertEquals( "Staves", \r8\Words::pluralize("Staff") );
        $this->assertEquals( "staves", \r8\Words::pluralize("sTAFF") );
    }

    public function testInt2Ordinal ()
    {
        $this->assertEquals( "1st", \r8\Words::ordinal(1) );
        $this->assertEquals( "2nd", \r8\Words::ordinal(2) );
        $this->assertEquals( "3rd", \r8\Words::ordinal(3) );
        $this->assertEquals( "4th", \r8\Words::ordinal(4) );
        $this->assertEquals( "5th", \r8\Words::ordinal(5) );
        $this->assertEquals( "6th", \r8\Words::ordinal(6) );
        $this->assertEquals( "7th", \r8\Words::ordinal(7) );
        $this->assertEquals( "8th", \r8\Words::ordinal(8) );
        $this->assertEquals( "9th", \r8\Words::ordinal(9) );
        $this->assertEquals( "10th", \r8\Words::ordinal(10) );

        $this->assertEquals( "11th", \r8\Words::ordinal(11) );
        $this->assertEquals( "12th", \r8\Words::ordinal(12) );
        $this->assertEquals( "13th", \r8\Words::ordinal(13) );
        $this->assertEquals( "14th", \r8\Words::ordinal(14) );
        $this->assertEquals( "15th", \r8\Words::ordinal(15) );
        $this->assertEquals( "16th", \r8\Words::ordinal(16) );
        $this->assertEquals( "17th", \r8\Words::ordinal(17) );
        $this->assertEquals( "18th", \r8\Words::ordinal(18) );
        $this->assertEquals( "19th", \r8\Words::ordinal(19) );
        $this->assertEquals( "20th", \r8\Words::ordinal(20) );

        $this->assertEquals( "21st", \r8\Words::ordinal(21) );
        $this->assertEquals( "22nd", \r8\Words::ordinal(22) );
        $this->assertEquals( "23rd", \r8\Words::ordinal(23) );
        $this->assertEquals( "24th", \r8\Words::ordinal(24) );
        $this->assertEquals( "25th", \r8\Words::ordinal(25) );
        $this->assertEquals( "30th", \r8\Words::ordinal(30) );

        $this->assertEquals( "-1st", \r8\Words::ordinal(-1) );
        $this->assertEquals( "-2nd", \r8\Words::ordinal(-2) );
        $this->assertEquals( "-3rd", \r8\Words::ordinal(-3) );
        $this->assertEquals( "-4th", \r8\Words::ordinal(-4) );
        $this->assertEquals( "-5th", \r8\Words::ordinal(-5) );
        $this->assertEquals( "-9th", \r8\Words::ordinal(-9) );
        $this->assertEquals( "-10th", \r8\Words::ordinal(-10) );
    }

}

?>