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

namespace r8\Test;

/**
 * Base unit testing suite class
 *
 * Provides an interface to search and load test suites in a directory
 */
class Suite extends \PHPUnit_Framework_TestSuite
{

    /**
     * Recursively collects a list of test files relative to the given base directory
     *
     * @param String $base The base directory to search in
     * @param String $dir A subdirectory of the base to search in
     * @return array
     */
    private function collectFiles ( $base, $dir = FALSE )
    {

        $base = rtrim($base, "/" ) ."/";

        if ( $dir ) {
            $dir = trim($dir, "/") ."/";
            $search = $base . $dir;
        }
        else {
            $search = $base;
        }

        $result = array();

        $list = scandir( $search );

        foreach ( $list AS $file ) {

            if ( substr($file, 0, 1) == "." )
                continue;

            if ( is_dir( $search . $file ) )
                $result = array_merge( $result, $this->collectFiles( $base, $dir . $file ) );

            else if ( preg_match('/.+\.php$/i', $file) )
                $result[] = $dir . $file;

        }

        sort( $result );

        return $result;
    }

    /**
     * Searches a given directory for PHP files and adds the contained tests to the current suite
     *
     * @param String $testPrefix
     * @param String $dir The directory to search in
     * @param String $exclude The file name to exclude from the search
     * @return object Returns a self reference
     */
    public function addFromFiles ( $testPrefix, $dir, $exclude )
    {

        $dir = rtrim($dir, "/" ) ."/";

        $list = $this->collectFiles($dir);

        foreach ( $list AS $file ) {

            if ( $file == $exclude )
                continue;

            require_once $dir . $file;

            $file = str_replace( ".php", "", $file );
            $file = str_replace( "/", "_", $file );

            if ( !class_exists($testPrefix . $file) )
                throw new Exception("Could not find unit test: ". $testPrefix . $file);

            $this->addTestSuite( $testPrefix . $file );
        }

        return $this;
    }

}

?>