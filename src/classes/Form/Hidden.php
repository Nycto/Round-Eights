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
 * @package Forms
 */

namespace r8\Form;

/**
 * Hidden form fields
 */
class Hidden extends \r8\Form\Field
{

    /**
     * Returns whether this field should be considered a Hidden field
     *
     * @return Boolean
     */
    public function isHidden ()
    {
        return TRUE;
    }

    /**
     * Returns a \r8\HTML\Tag object that represents this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag()
    {
        return parent::getTag()
            ->setAttr("type", "hidden");
    }

    /**
     * Provides an interface for visiting this field
     *
     * @param \r8\iface\Form\Visitor $visitor The visitor object to call
     * @return NULL
     */
    public function visit ( \r8\iface\Form\Visitor $visitor )
    {
        $visitor->hidden( $this );
    }

}

?>