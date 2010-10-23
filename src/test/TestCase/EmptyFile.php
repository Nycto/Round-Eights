<?php
/**
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
 * @package PHPUnit
 */

namespace r8\Test\TestCase;

/**
 * Base test class for tests that require an empty temporary file
 */
abstract class EmptyFile extends \PHPUnit_Framework_TestCase
{

    /**
     * This is a list of all the files created with getTempFileName. They will
     * automatically be removed on teardown
     */
    private $cleanup = array();

    /**
     * The name of the temporary file
     */
    protected $file;

    /**
     * Returns the name of a temporary file
     *
     * This does not create the file, it mearly returns a unique, temporary path
     *
     * @return string
     */
    public function getTempFileName ()
    {
        $result = rtrim( sys_get_temp_dir(), "/" ) ."/r8_unitTest_". uniqid();
        $this->cleanup[] = $result;
        return $result;
    }

    /**
     * Setup creates the file
     */
    public function setUp ()
    {
        $this->file = $this->getTempFileName();

        if ( !@touch( $this->file ) )
            $this->markTestSkipped("Unable to create temporary file");
    }

    /**
     * Teardown will automatically remove the file
     */
    public function tearDown ()
    {
        foreach ( $this->cleanup AS $file ) {

            if ( file_exists($file) ) {

                // Fix the permissions so we can delete it
                if ( !is_writable($file) )
                    @chmod($file, 0600);

                @unlink( $file );

            }
        }
    }

}

