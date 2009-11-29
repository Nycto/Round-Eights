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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Error
 */

namespace r8\Error\formatter;

/**
 * Formats an error as HTML
 */
class HTML implements \r8\iface\Error\Formatter
{

    /**
     * The environment of the current request
     *
     * @var \r8\iface\Env\Request
     */
    private $request;

    /**
     * The formatter to use for displaying the debug backtrace
     *
     * @var \r8\Backtrace\Formatter
     */
    private $formatter;

    /**
     * Constructor...
     *
     * @param \r8\iface\Env\Request $request The environment of the current request
     * @param \r8\Backtrace\Formatter $formatter The formatter to use for displaying
     *         the debug backtrace
     */
    public function __construct (
        \r8\iface\Env\Request $request,
        \r8\Backtrace\Formatter $formatter
    ) {
        $this->request = $request;
        $this->formatter = $formatter;
    }

    /**
     * Formats an error as a string
     *
     * @param \r8\iface\Error $error The error to format
     * @return String Returns the formatted error
     */
    public function format ( \r8\iface\Error $error )
    {
        $type = htmlspecialchars( $error->getType() );
        $message = htmlspecialchars( $error->getMessage() );
        $code = htmlspecialchars( $error->getCode() );
        $line = $error->getLine();
        $file = htmlspecialchars( $error->getFile() );
        $backtrace = $this->formatter->format( $error->getBacktrace() );
        $uri = htmlspecialchars( $this->request->getURL()->__toString() );

        return <<<END_OF_ERROR
<table>
    <tr>
        <th colspan='2'>Error Encountered</th>
    </tr>
    <tr>
        <td>Type</td>
        <td>{$type}</td>
    </tr>
    <tr>
        <td>Message</td>
        <td>{$message}</td>
    </tr>
    <tr>
        <td>Code</td>
        <td>{$code}</td>
    </tr>
    <tr>
        <td>In File</td>
        <td>{$file}</td>
    </tr>
    <tr>
        <td>On Line</td>
        <td>{$line}</td>
    </tr>
    <tr>
        <td>URI</td>
        <td>{$uri}</td>
    </tr>
    <tr>
        <th colspan='2'>Backtrace</th>
    </tr>
    <tr>
        <td>{$backtrace}</td>
    </tr>
</table>
END_OF_ERROR;
    }

}

?>