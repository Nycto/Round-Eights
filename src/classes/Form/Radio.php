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
 * An HTML radio button field list
 */
class Radio extends \r8\Form\Multi
{

    /**
     * Returns the HTML ID that will be used to identify each radio button
     *
     * The fields need to have an ID so that the label tags are correctly
     * associated with the radio tags
     *
     * @return String
     */
    public function getRadioOptionID ( $value )
    {
        $value = \r8\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \r8\Exception\Index($value, "Option Value", "Option does not exist in field");

        return "radio_"
            .\r8\str\stripW( $this->getName() )
            ."_"
            .substr(sha1($value), 0, 10);
    }

    /**
     * Returns the an HTML tag that represents an individual option's radio button
     *
     * @param String|Integer $value The value of the option whose tag should be returned
     * @return \r8\HTML\Tag
     */
    public function getOptionRadioTag ( $value )
    {
        $value = \r8\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \r8\Exception\Index($value, "Option Value", "Option does not exist in field");

        $tag = new \r8\HTML\Tag( 'input' );

        $tag->importAttrs(array(
                "name" => $this->getName(),
                "value" => $value,
                "type" => "radio",
                "id" => $this->getRadioOptionID($value)
            ));

        if ( $value == $this->getValue())
            $tag['checked'] = 'checked';

        return $tag;
    }

    /**
     * Returns the an HTML tag that represents an individual option's label
     *
     * @param String|Integer $value The value of the option whose label tag should be returned
     * @return \r8\HTML\Tag
     */
    public function getOptionLabelTag ( $value )
    {
        $value = \r8\indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new \r8\Exception\Index($value, "Option Value", "Option does not exist in field");

        return new \r8\HTML\Tag(
                'label',
                $this->getOptionLabel( $value ),
                array( "for" => $this->getRadioOptionID($value) )
            );
    }

    /**
     * Returns a string representation of the option list
     *
     * @return String The list of radio buttons and their labels
     */
    public function getOptionList ()
    {
        $result = "";

        foreach ( $this->getOptions() AS $key => $value ) {
            $result .= "<li>"
                .$this->getOptionRadioTag( $key )
                ." "
                .$this->getOptionLabelTag( $key )
                ."</li>";
        }

        return $result;
    }

    /**
     * Returns a \r8\HTML\Tag object that represents this instance
     *
     * @return \r8\HTML\Tag
     */
    public function getTag()
    {
        return new \r8\HTML\Tag(
                'ul',
                $this->getOptionList()
            );
    }

}

?>