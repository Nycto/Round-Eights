<?php
/**
 * File Finder Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package FileFinder
 */

namespace cPHP;

/**
 * The base File Finder class
 */
abstract class FileFinder
{

    /**
     * The FileFinder class to fall back on if the file can not be found by
     * this instance
     */
    private $fallback;

    /**
     * Returns the fallback class for this instance
     *
     * @return Object|Null Returns a FileFinder instance, or NULL if no fallback
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
     * @param Object $fallback The fallback instance
     * @return Object Returns a self reference
     */
    public function setFallback ( \cPHP\FileFinder $fallback )
    {

        // Ensure that this doesn't form a loop
        $check = $fallback;
        while ( $check->fallbackExists() ) {
            $check = $check->getFallback();
            if ( $check === $this )
                throw new \cPHP\Exception\Interaction("Setting Fallback creates an infinite loop");
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
     * @return Object Returns a self reference
     */
    public function clearFallback ()
    {
        $this->fallback = null;
        return $this;
    }

    /**
     * Returns the top-most fallback in this chain that doesn't have a fallback
     *
     * @return Object
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
     */
    abstract protected function internalFind ( $file );

}

?>