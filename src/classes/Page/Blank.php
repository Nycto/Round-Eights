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
 * @package Page
 */

namespace r8\Page;

/**
 * Displays a blank page
 */
class Blank implements \r8\iface\Page
{

    /**
     * Returns the core content this page will display
     *
     * @param \r8\Page\Context $context A context object which is used by this
     *      page to communicate with the root page
     * @return \r8\Template\Blank Returns a blank template
     */
    public function getContent ( \r8\Page\Context $context )
    {
        return new \r8\Template\Blank;
    }

}

