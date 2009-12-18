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

namespace r8\Stream\Out;

/**
 * Writes data to the StdErr
 */
class StdErr implements \r8\iface\Stream\Out
{

    /**
     * Writes a string of data to this stream
     *
     * @param String $data The string of data to to write to this stream
     * @return \r8\Stream\Out\StdErr Returns a self reference
     */
    public function write ( $data )
    {
        fwrite( STDERR, \r8\strval( $data ) );
        return $this;
    }

}

?>