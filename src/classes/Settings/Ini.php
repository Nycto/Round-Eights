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
 * @package Settings
 */

namespace r8\Settings;

/**
 * Pulls Settings an ini file
 *
 * The formatting of this ini file is the same as in php.ini. For more details,
 * see the parse_ini_file method:
 * http://us.php.net/manual/en/function.parse-ini-file.php
 */
class Ini implements \r8\iface\Settings\Read
{

    /**
     * The ini file to parse
     *
     * @var String
     */
    private $file;

    /**
     * Once parsed, the content of the ini file
     *
     * @var Array
     */
    private $settings;

    /**
     * Constructor...
     *
     * @param String $file The ini file to parse
     */
    public function __construct ( $file )
    {
        $this->file = (string) $file;
    }

    /**
     * Lazily loads the data from the ini file into this instance
     *
     * @return NULL
     */
    private function load ()
    {
        if ( !is_file($this->file) )
            throw new \r8\Exception\FileSystem\Missing($this->file, "Ini file does not exist");

        if ( !is_readable($this->file) )
            throw new \r8\Exception\FileSystem\Permissions($this->file, "Ini file is not readable");

        $settings = parse_ini_file( $this->file, TRUE );

        if ( $settings === FALSE )
            throw new \r8\Exception\FileSystem($this->file, "Ini file could not be parsed");

        $this->settings = $settings;
    }

    /**
     * Returns a the value of a setting
     *
     * @param String $group The higher level group in which to look for the key
     * @param String $key The key to pull
     * @return Mixed
     */
    public function get ( $group, $key )
    {
        if ( !isset($this->settings) )
            $this->load();

        if ( isset($this->settings[$group][$key]) )
            return $this->settings[$group][$key];
        else
            return NULL;
    }

    /**
     * Returns whether a setting exists
     *
     * @param String $group The higher level group in which to look for the key
     * @param String $key TThe key to look up
     * @return Boolean
     */
    public function exists ( $group, $key )
    {
        if ( !isset($this->settings) )
            $this->load();
    }

    /**
     * Returns all the values from a group as a Key/Value list
     *
     * @param String $group The higher level group to pull
     * @return Array
     */
    public function getGroup ( $group )
    {
        if ( !isset($this->settings) )
            $this->load();
    }

}

?>