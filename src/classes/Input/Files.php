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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Input
 */

namespace r8\Input;

/**
 * A collection of uploaded files
 */
class Files
{

    /**
     * The list of files registered in this list
     *
     * @var Array
     */
    private $files = array();

    /**
     * Constructor...
     *
     * @param Array $fileList The array of files to register, indexed
     * 		by their field name
     */
    public function __construct ( array $fileList = array() )
    {
        foreach ( $fileList AS $key => $file ) {

            if ( $file instanceof \r8\Input\File ) {
                $this->files[$key] = $file;
            }

            else if ( is_array($file) ) {

                $file = array_filter(
                    $file,
                    new \r8\Curry\Call( "is_a", '\r8\Input\File' )
                );

                if ( !empty($file) )
                    $this->files[$key] = array_values( $file );
            }

        }

    }

    /**
     * Returns the list of registered files
     *
     * @return Array
     */
    public function getFiles ()
    {
        return $this->files;
    }

}

?>