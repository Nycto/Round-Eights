<?php
/**
 * HTML Form Field
 *
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
 * @package Forms
 */

namespace r8\Form\Field;

/**
 * A checkbox form field
 */
class Checkbox extends \r8\Form\Field
{

    /**
     * Constructor...
     *
     * Sets the default boolean filter
     *
     * @param String The name of this form field
     */
    public function __construct( $name )
    {
        parent::__construct( $name );

        $this->setFilter(
                new \r8\Filter\Boolean
            );
    }

    /**
     * Returns a \r8\Tag object that represents this instance
     *
     * @return Object A \r8\Tag object
     */
    public function getTag()
    {
        $tag = new \r8\Tag(
                'input',
                null,
                array(
                        "type" => 'checkbox',
                        "value" => 'on',
                        "name" => $this->getName()
                    )
            );

        if ( $this->getValue() )
            $tag->setAttr("checked", "checked");

        return $tag;
    }

}

?>