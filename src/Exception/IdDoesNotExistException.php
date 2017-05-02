<?php


namespace MinhD\OAIPMH\Exception;


use MinhD\OAIPMH\OAIException;

class IdDoesNotExistException extends OAIException
{
    public function getErrorName()
    {
        return "idDoesNotExist";
    }
}