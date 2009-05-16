<?php
/**
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
 * @package Stream
 */

namespace cPHP\Stream\Out;

/**
 * Writes data to the StdOut
 *
 * This simply means echoing the data to the client.
 */
class StdOut implements \cPHP\iface\Stream\Out
{

    /**
     * Writes a string of data to this stream
     *
     * @param String $data The string of data to to write to this stream
     * @return \cPHP\Stream\Out\StdOut Returns a self reference
     */
    public function write ( $data )
    {
        echo \cPHP\strval( $data );
        unset( $data );
        return $this;
    }

}

?>