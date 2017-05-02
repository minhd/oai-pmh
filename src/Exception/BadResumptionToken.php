<?php


namespace MinhD\OAIPMH\Exception;


use MinhD\OAIPMH\OAIException;

class BadResumptionToken extends OAIException
{
    public function getErrorName()
    {
        return "Bad resumptionToken";
    }
}