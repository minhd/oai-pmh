<?php


namespace MinhD\OAIPMH\Exception;


use MinhD\OAIPMH\OAIException;

class BadArgumentException extends OAIException
{
    public function getErrorName()
    {
        return "badArgument";
    }
}