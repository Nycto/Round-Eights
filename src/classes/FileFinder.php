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
 * @package FileFinder
 */

namespace r8;

/**
 * The base File Finder class
 */
abstract class FileFinder
{

    /**
     * The FileFinder class to fall back on if the file can not be found by
     * this instance
     *
     * @var \r8\FileFinder
     */
    private $fallback;

    /**
     * Returns the fallback class for this instance
     *
     * @return \r8\FileFinder|Null Returns the fallback, or NULL if none
     *      has been set
     */
    public function getFallback ()
    {
        return $this->fallback;
    }

    /**
     * Sets the FileFinder class to fall back on if the file can not be found by
     * this instance
     *
     * @param \r8\FileFinder $fallback The fallback instance
     * @return \r8\FileFinder Returns a self reference
     */
    public function setFallback ( \r8\FileFinder $fallback )
    {
        // Ensure that this doesn't form a loop
        $check = $fallback;
        while ( $check->fallbackExists() ) {
            $check = $check->getFallback();
            if ( $check === $this )
                throw new \r8\Exception\Interaction("Setting Fallback creates an infinite loop");
        }

        $this->fallback = $fallback;
        return $this;
    }

    /**
     * Returns whether this instance has a fallback
     *
     * @return Boolean
     */
    public function fallbackExists ()
    {
        return isset($this->fallback);
    }

    /**
     * Clears the fallback from this instance
     *
     * @return \r8\FileFinder Returns a self reference
     */
    public function clearFallback ()
    {
        $this->fallback = null;
        return $this;
    }

    /**
     * Returns the top-most file finder in this chain that doesn't have a fallback
     *
     * @return \r8\FileFinder
     */
    public function getTopFallback ()
    {
        $fallback = $this;

        while ( $fallback->fallbackExists() ) {
            $fallback = $fallback->getFallback();
        }

        return $fallback;
    }

    /**
     * Internal method that actual searches this instance for the file
     *
     * @param String $file The file being looked for
     * @return String|False This should return FALSE if the file couldn't be
     *      found, or the path if it was.
     */
    abstract protected function internalFind ( $file );

    /**
     * Attempts to find a file
     *
     * @param String|Array $file The file being looked for. If an array is given,
     *      all the values will be tested at this level before moving on to the
     *      fallback.
     * @return NULL|\r8\FileSys\File Returns the found file
     *      Returns NULL if the file couldn't be found
     */
    public function find ( $file )
    {

        // If it isn't traversable, convert it to an array
        if ( !is_array( $file ) )
            $file = array( $file );

        foreach ( $file AS $current ) {

            $current = ltrim( \r8\FileSys::resolvePath( $current ), "/" );

            $result = $this->internalFind( $current );

            if ( $result instanceof \r8\FileSys )
                return $result;

        }

        // If we reach here, then nothing was found by this instance
        if ( $this->fallbackExists() )
            return $this->getFallback()->find( $file );
        else
            return NULL;

    }

}

?>