<?php
/**
 * Exception Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace h2o\Exception;

/**
 * An exception class to describe errors caused when code incorrectly
 * interfaces with other code
 */
class Interaction extends \h2o\Exception
{

    /**
     * The title of this exception
     */
    const TITLE = "Interaction Error";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused when code incorrectly interfaces with other code";

}

?>