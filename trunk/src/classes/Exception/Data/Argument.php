<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception::Data;

/**
 * Exception class to handle bad arguments
 */
class Argument extends ::cPHP::Exception::Data
{
    protected $exception = "Argument Error";
    protected $description = "Errors caused by faulty arguments";

    /**
     * The argument at fault
     */
    protected $arg;

    /**
     * Constructor
     *
     * @param Integer $arg The argument offset (from 0) that caused the error
     * @param String $label The name of the argument
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($arg, $label = NULL, $message = NULL, $code = 0, $fault = 0)
    {
        parent::__construct(NULL, $label, $message, $code, $fault);
        if (!is_null($arg) && !is_bool($arg) && $this->getTraceCount() > 0)
            $this->setArg($arg, RESTRICT);
    }

    /**
     * Identify the argument that caused the problem
     */
    public function setArg ($offset, $wrapFlag = RESTRICT)
    {
        $fault = $this->getFault();
        if ($fault === FALSE || !array_key_exists('args', $fault) || !is_array($fault['args']))
            trigger_error("Error fetching fault trace arguments", E_USER_ERROR);

        if (count($fault['args']) <= 0)
            return FALSE;

        $offset = calcWrapFlag (count($fault['args']), $offset, $wrapFlag);

        if (is_int($offset))
            $this->arg = $offset;
        else
            unset($this->arg);

        return $offset;
    }

    /**
     * Get the integer offset of the problem integer
     */
    public function getArgOffset ()
    {
        return $this->arg;
    }

    /**
     * Boolean whether or not an arg is set
     */
    public function issetArg ()
    {
        return isset($this->arg);
    }

    /**
     * Change the fault
     */
    public function setFault ($offset, $arg = NULL)
    {
        $result = parent::setFault($offset);

        if (!is_null($arg) && !is_bool($arg))
            $this->setArg( $arg );
        else if (isset($this->arg))
            $this->setArg( $this->arg );

        return $result;
    }

    /**
     * Get the value of the argument at fault
     */
    public function getArgData ()
    {
        $trace = $this->getFault();
        if (count($trace['args']) <= 0)
            return NULL;
        return $trace['args'][ $this->getArgOffset() ];
    }

    /**
     * Returns specifics about this exception
     */
    public function getDetailsString ()
    {
        if (!$this->issetMessage() && !$this->issetCode() && !$this->issetData() && !$this->issetLabel())
            return NULL;
        else
            return "Details:\n"
                .($this->issetCode()?"  Code: ". $this->getCode() ."\n":"")
                .($this->issetLabel()?"  ". $this->label_string .": ". $this->getLabel() ."\n":"")
                .($this->issetArg()?"  Arg #: ". ($this->getArgOffset() + 1) ."\n":"")
                .($this->issetData()?"  ". $this->data_string .": ". $this->getDataString() ."\n":"")
                .($this->issetMessage()?"  Message: ". $this->getMessage() ."\n":"");
    }

}

?>