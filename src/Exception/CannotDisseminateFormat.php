<?php


namespace MinhD\OAIPMH\Exception;


use MinhD\OAIPMH\OAIException;

class CannotDisseminateFormat extends OAIException
{
    public function getErrorName()
    {
        return "cannotDisseminateFormat";
    }
}