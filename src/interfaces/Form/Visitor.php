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

namespace r8\iface\Form;

/**
 * Form field visitor
 */
interface Visitor
{

    /**
     * Invoked at the beginning of a visit
     *
     * @param \r8\Form $form The form being visited
     * @return NULL
     */
    public function begin ( \r8\Form $form );

    /**
     * Visitor callback for a checkbox field
     *
     * @param \r8\Form\Checkbox $field
     * @return NULL
     */
    public function checkbox ( \r8\Form\Checkbox $field );

    /**
     * Visitor callback for a file field
     *
     * @param \r8\Form\File $field
     * @return NULL
     */
    public function file ( \r8\Form\File $field );

    /**
     * Visitor callback for a hidden field
     *
     * @param \r8\Form\Hidden $field
     * @return NULL
     */
    public function hidden ( \r8\Form\Hidden $field );

    /**
     * Visitor callback for a password field
     *
     * @param \r8\Form\Password $field
     * @return NULL
     */
    public function password ( \r8\Form\Password $field );

    /**
     * Visitor callback for a radio field
     *
     * @param \r8\Form\Radio $field
     * @return NULL
     */
    public function radio ( \r8\Form\Radio $field );

    /**
     * Visitor callback for a select field
     *
     * @param \r8\Form\Select $field
     * @return NULL
     */
    public function select ( \r8\Form\Select $field );

    /**
     * Visitor callback for a text field
     *
     * @param \r8\Form\Text $field
     * @return NULL
     */
    public function text ( \r8\Form\Text $field );

    /**
     * Visitor callback for a text area field
     *
     * @param \r8\Form\TextArea $field
     * @return NULL
     */
    public function textArea ( \r8\Form\TextArea $field );

    /**
     * Invoked at the end of a visit
     *
     * @param \r8\Form $form The form being visited
     * @return Mixed Returns whatever value should be the result of the visit
     */
    public function end ( \r8\Form $form );

}

