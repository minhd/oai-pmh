<?php

namespace MinhD\OAIPMH\Interfaces;

interface OAIRepository
{
    public function identify();
    public function listSets();
    public function getDateFormat();
//    public function listSetsByToken($token);
//    public function getRecord($metadataFormat, $identifier);
//    public function listRecords($metadataFormat = null, \DateTime $from = null, \DateTime $until = null, $set = null);
//    public function listRecordsByToken($token);
    public function listMetadataFormats($identifier = null);
}