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
 * @package Stream
 */

namespace r8\Stream\In;

/**
 * Provides a Stream interface for reading a File
 */
class File extends \r8\Stream\In\URI
{

    /**
     * Constructor...
     *
     * @param \r8\FileSys\File $file The file being opened
     */
    public function __construct ( \r8\FileSys\File $file )
    {
        $file->requirePath();

        if ( !$file->isReadable() ) {
            throw new \r8\Exception\FileSystem\Permissions(
                    $file->getPath(),
                    "File is not readable"
                );
        }

        parent::__construct( $file->getPath() );
    }

}

?>