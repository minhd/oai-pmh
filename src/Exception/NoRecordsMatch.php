<?php


namespace MinhD\OAIPMH\Exception;

use MinhD\OAIPMH\OAIException;

class NoRecordsMatch extends OAIException
{
    public function getErrorName()
    {
        return "noRecordsMatch";
    }
}