<?php
/**
 * Email sender
 *
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
 * @package Mail
 */

namespace cPHP;

/**
 * Handles sending a piece of mail
 */
class Mail
{

    /**
     * The maximum number of characters a single line can contain
     */
    const LINE_LENGTH = 750;

    /**
     * The end of line character to use
     */
    const EOL = "\n";

    /**
     * The email address being sent to
     */
    protected $to;

    /**
     * The actual name of the person being sent to
     */
    protected $toName;

    /**
     * The email address this message will be sent from
     */
    protected $from;

    /**
     * The actual name of the person this message was sent from
     */
    protected $fromName;

    /**
     * The subject of the message
     */
    protected $subject;

    /**
     * Any email addresses to cc the message to
     */
    protected $cc = array();

    /**
     * The raw text of the message
     */
    protected $text;

    /**
     * The HTML of the message
     */
    protected $html;


}

?>