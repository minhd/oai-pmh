<?php

namespace MinhD\OAIPMH\Interfaces;

interface OAIRepository
{
    public function identify();
    public function listSets($limit = 0, $offset = 0);
//    public function listSetsByToken($token);
//    public function getRecord($metadataFormat, $identifier);
//    public function listRecords($metadataFormat = null, $set = null, \DateTime $from = null, \DateTime $until = null);
//    public function listRecordsByToken($token);
    public function listMetadataFormats($identifier = null);

    // helper
    public function getDateFormat();
}