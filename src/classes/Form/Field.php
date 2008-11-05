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
abstract class Field implements ::cPHP::iface::Form::Field
{

    /**
     * The name of this form field
     */
    private $name;

    /**
     * The current, raw value of this field
     *
     * The value stored here is unfiltered and unvalidated
     */
    protected $value;

    /**
     * The filter to apply to any data that is fed in to this field
     */
    private $filter;

    /**
     * The validator for this field
     */
    private $validator;

    /**
     * Constructor...
     *
     * @param String $name The name of this form field
     */
    public function __construct( $name )
    {
        $this->setName( $name );
    }

    /**
     * Returns the name of this field
     *
     * @return String
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * Sets the name of this field
     *
     * @param String $name The field name
     * @return Object returns a self reference
     */
    public function setName( $name )
    {
        $name = ::cPHP::Filter::Variable()->filter( $name );

        if ( !::cPHP::Validator::Variable()->isValid( $name ) )
            throw new ::cPHP::Exception::Argument( 0, "Field Name", "Must be a valid PHP variable name" );

        $this->name = $name;

        return $this;
    }

    /**
     * Returns the filter loaded in to this instance
     *
     * If no filter has been explicitly set, this will create a new chain
     * filter, save it to this instance and return a reference to it
     *
     * @return Object Returns a filter object
     */
    public function getFilter ()
    {
        if ( !($this->filter instanceof ::cPHP::iface::Filter) )
            $this->filter = new ::cPHP::Filter::Chain;

        return $this->filter;
    }

    /**
     * Sets the filter for this instance
     *
     * @param Object An object that implements the cPHP::iface::Filter interface
     * @return Object Returns a self reference
     */
    public function setFilter( ::cPHP::iface::Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Returns the validator loaded in to this instance
     *
     * If no validator has been explicitly set, this will create a new "Any"
     * validator, save it to this instance and return a reference to it
     *
     * @return Object Returns a Validator object
     */
    public function getValidator ()
    {
        if ( !($this->validator instanceof ::cPHP::iface::Validator) )
            $this->validator = new ::cPHP::Validator::Collection::Any;

        return $this->validator;
    }

    /**
     * Sets the validator for this instance
     *
     * @param Object A validator object
     * @return Object Returns a self reference
     */
    public function setValidator( ::cPHP::iface::Validator $validator )
    {
        $this->validator = $validator;
        return $this;
    }

    /**
     * Adds another validator to this instance
     *
     * This checks to see if the current validator is an "Collection::All" instance.
     * If it is, then it adds the given validator on to the list. If it isn't,
     * then it wraps the current validator and the validator in the instance in
     * an All validator and sets it to the validator for this instance.
     *
     * @param Object $validator The validator to add to this instance
     * @return Object Returns a self reference
     */
    public function andValidator ( ::cPHP::iface::Validator $validator )
    {
        if ( $this->validator instanceof ::cPHP::Validator::Collection::All ) {
            $this->validator->add( $validator );
        }
        else {
            $this->validator = new ::cPHP::Validator::Collection::All(
                    $this->validator,
                    $validator
                );
        }

        return $this;
    }

    /**
     * Returns the unfiltered, unvalidated value that is contained in this instance
     *
     * @return mixed The raw value of this field
     */
    public function getRawValue ()
    {
        return $this->value;
    }

    /**
     * Sets the value for this field
     *
     * This does not apply the filter when saving, however it will convert any
     * objects or arrays using the ::cPHP::reduce function
     *
     * @param mixed $value The value of this field
     * @return Object Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = ::cPHP::reduce( $value );
        return $this;
    }

    /**
     * Applies the filter and returns the resultting value
     *
     * @return mixed The filtered value
     */
    public function getValue ()
    {
        // Only apply the filter if there is one
        if ( $this->filter instanceof cPHP::iface::Filter )
            return $this->filter->filter( $this->getRawValue() );
        else
            return $this->getRawValue();
    }

    /**
     * Applies the validator to the value in this instance and returns an
     * instance of Validator Results.
     *
     * This will apply the validator to the filtered value
     *
     * @result object An instance of validator results
     */
    public function validate ()
    {
        return $this->getValidator()->validate( $this->getValue() );
    }

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @return Boolean
     */
    public function isValid ()
    {
        return $this->validate()->isValid();
    }

    /**
     * Returns a cPHP::Tag object that represents this instance
     *
     * @return Object A cPHP::Tag object
     */
    public function getTag()
    {
        return new ::cPHP::Tag(
                'input',
                null,
                array(
                        "value" => $this->getValue(),
                        "name" => $this->getName()
                    )
            );
    }

    /**
     * Converts this field to an HTML string
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getTag()->__toString();
    }

}

?>