<?php
/**
 * A file upload form field
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

namespace cPHP\Form\Field;

/**
 * A form field used to upload files
 */
class File extends \cPHP\Form\Field
{

    /**
     * Returns the value of the $_FILE variable
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Array
     */
    protected function getUploadedFiles ()
    {
        return $_FILES;
    }

    /**
     * Returns a new FileUpload validator
     *
     * This has been added to make it easier to unit test. By mocking this class
     * and overwriting this method, you can make the rest of the methods think
     * that a file was uploaded
     *
     * @return Object A FileUpload validator
     */
    protected function getFileUploadValidator ()
    {
        return new \cPHP\Validator\FileUpload;
    }

    /**
     * Returns the temporary filename of the uploaded file
     *
     * @return mixed The raw value of this field
     */
    public function getRawValue ()
    {
        $files = $this->getUploadedFiles();

        if ( isset($files[ $this->getName() ]) )
            return $files[ $this->getName() ]['tmp_name'];

        return null;
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
        // Apply the FileUpload validator before anything else
        $result = $this->getFileUploadValidator()->validate( $this->getName() );

        // If it fails, don't even give the other validators a chance
        if ( !$result->isValid() )
            return $result;

        return parent::validate();
    }

    /**
     * Returns a \cPHP\Tag object that represents this instance
     *
     * @return Object A \cPHP\Tag object
     */
    public function getTag()
    {
        return parent::getTag()
            ->unsetAttr('value')
            ->setAttr("type", "file");
    }

}

?>