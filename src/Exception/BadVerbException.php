<?php


namespace MinhD\OAIPMH\Exception;


use MinhD\OAIPMH\OAIException;

class BadVerbException extends OAIException
{
    public function getErrorName()
    {
        return "badVerb";
    }
}