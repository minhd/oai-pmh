<?php


namespace MinhD\OAIPMH;


class OAIException extends \Exception
{
    public $oaiErrorCode = "badArgument";

    /**
     * OAIException constructor.
     * @param string $oaiErrorCode
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     * @param string $oaiErrorCode
     */
    public function __construct($oaiErrorCode = "badArgument", $message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->oaiErrorCode = $oaiErrorCode;
    }

    /**
     * @return string
     */
    public function getOaiErrorCode()
    {
        return $this->oaiErrorCode;
    }

    /**
     * @param string $oaiErrorCode
     */
    public function setOaiErrorCode($oaiErrorCode)
    {
        $this->oaiErrorCode = $oaiErrorCode;
    }

}