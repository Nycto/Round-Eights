<?php
/**
 * An HTML radio button list
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

namespace cPHP::Form::Field;

/**
 * An HTML radio button field list
 */
class Radio extends ::cPHP::Form::Multi
{

    /**
     * Returns the HTML ID that will be used to identify each radio button
     *
     * The fields need to have an ID so that the label tags are correctly
     * associated with the radio tags
     *
     * @return
     */
    public function getRadioOptionID ( $value )
    {
        $value = ::cPHP::indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new ::cPHP::Exception::Index($value, "Option Value", "Option does not exist in field");

        return "radio_"
            .::cPHP::stripW( $this->getName() )
            ."_"
            .substr(sha1($value), 0, 10);
    }

    /**
     * Returns the an HTML tag that represents an individual option's radio button
     *
     * @param String|Integer $value The value of the option whose tag should be returned
     * @return Object Returns a cPHP::Tag object
     */
    public function getOptionRadioTag ( $value )
    {
        $value = ::cPHP::indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new ::cPHP::Exception::Index($value, "Option Value", "Option does not exist in field");

        $tag = new ::cPHP::Tag( 'input' );

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
     * @return Object Returns a cPHP::Tag object
     */
    public function getOptionLabelTag ( $value )
    {
        $value = ::cPHP::indexVal( $value );

        if ( !$this->hasOption($value) )
            throw new ::cPHP::Exception::Index($value, "Option Value", "Option does not exist in field");

        return new ::cPHP::Tag(
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
        return $this->getOptions()->collect(function ($value, $key) {
            return "<li>"
                .$this->getOptionRadioTag( $key )
                ." "
                .$this->getOptionLabelTag( $key )
                ."</li>";
        })->implode();
    }

    /**
     * Returns a cPHP::Tag object that represents this instance
     *
     * @return Object A cPHP::Tag object
     */
    public function getTag()
    {
        return new ::cPHP::Tag(
                'ul',
                $this->getOptionList()
            );
    }

}

?>