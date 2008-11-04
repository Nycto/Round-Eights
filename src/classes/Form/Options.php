<?php
/**
 * A Basic HTML form field
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package Forms
 */

namespace cPHP::Form;

/**
 * The core class for HTML forms
 */
abstract class Options extends ::cPHP::Form::Field
{

    /**
     * The list of options
     *
     * This is an associative array where they key is the option value and
     * the element value is the option label
     */
    private $options = array();

    /**
     * Returns the list of registered options
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function getOptions ()
    {
        return new ::cPHP::Ary( $this->options );
    }

    /**
     * Adds a new option on to this list
     *
     * @param mixed $value The raw value of this option. This will be reduced
     *      down to a basic value
     * @param String $label The visible label for this value
     * @return Object Returns a self reference
     */
    public function addOption ( $value, $label )
    {
        $value = ::cPHP::reduce($value);
        $label = ::cPHP::strval( $label );

        $this->options[ $value ] = $label;

        return $this;
    }

    /**
     * Returns whether an option exists based on its value
     *
     * @param mixed $value The option value to test
     * @return Boolean
     */
    public function hasOption ( $value )
    {
        $value = ::cPHP::reduce($value);

        return array_key_exists( $value, $this->options );
    }

    /**
     * Removes an option from the list based on it's value
     *
     * @param mixed $value The option value to remove
     * @return Object Returns a self reference
     */
    public function removeOption ( $value )
    {
        $value = ::cPHP::reduce($value);

        if ( $this->hasOption( $value ) )
            unset($this->options[ $value ]);

        return $this;
    }

    /**
     * Removes all the registered options from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearOptions ()
    {
        $this->options = array();
        return $this;
    }

    /**
     * Imports a set of options from an array or traversable object
     *
     * @param mixed $source An array or a traversable object
     * @return Object Returns a self reference
     */
    public function importOptions ( $source )
    {
        if ( !::cPHP::Ary::is($source) )
            throw new ::cPHP::Exception::Argument(0, "Import Source", "Must be an array or a traversable object");

        $source = new ::cPHP::Ary( $source );

        $source->flatten()->each(function( $value, $key ) {
            $this->addOption($key, $value);
        });

        return $this;
    }

}

?>