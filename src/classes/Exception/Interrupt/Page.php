<?php
/**
 * Exception Class
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Exception
 */

namespace h2o\Exception\Interrupt;

/**
 * Page load Interruption exception
 *
 * This exception is used by the Page classes to interrupt the loading of a page
 * and immediately send control back to the root page
 */
class Page extends \h2o\Exception\Interrupt
{

    /**
     * The title of this exception
     */
    const TITLE = "Page Interruption";

    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Page load interruption";

}

?>