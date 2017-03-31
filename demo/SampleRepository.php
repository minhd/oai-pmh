<?php

use Carbon\Carbon;
use MinhD\OAIPMH\Interfaces\OAIRepository;
use MinhD\OAIPMH\Record;
use MinhD\OAIPMH\Set;

class SampleRepository implements OAIRepository {

    protected $payloadLimit = 100;

    private $identity = [
        'repositoryName' => 'Sample Name',
        'baseURL' => 'http://localhost.com',
        'protocolVersion' => '2.0',
        'adminEmail' => 'admin@localhost.com',
        'earliestDatestamp' => '2010-01-12T05:03:58Z',
        'deletedRecord' => 'transient',
        'granularity' => 'YYYY-MM-DDThh:mm:ssZ',
    ];

    public $dateFormat = "Y-m-d\\Th:m:s\\Z";

    private $metadataFormats = [
        [
            'metadataPrefix' => 'rif',
            'schema' => "http://services.ands.org.au/documentation/rifcs/1.3/schema/registryObjects.xsd",
            'metadataNamespace' => 'http://ands.org.au/standards/rif-cs/registryObjects
'
        ],
        [
            'metadataPrefix' => 'oai_dc',
            'schema' => 'http://www.openarchives.org/OAI/2.0/oai_dc.xsd',
            'metadataNamespace' => 'http://www.openarchives.org/OAI/2.0/oai_dc/'
        ]
    ];

    /**
     * @param array $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    public function identify()
    {
        return $this->identity;
    }

    public function listSets($limit = 0, $offset = 0)
    {
        $setCount = 250;

        $sets = [];
        for ($i = 1; $i <= $setCount; $i++) {
            $sets[] = new Set("testSet{$i}Spec", "testSet{$i}");
        }

        $total = count($sets);

        // get the limit and offset
        $sets = array_slice($sets, $offset, $limit);

        return compact('total', 'sets', 'limit', 'offset');
    }

    /**
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * @param null $identifier
     * @return array
     */
    public function listMetadataFormats($identifier = null)
    {
        // TODO identifier
        return $this->metadataFormats;
    }

    public function listIdentifiers($metadataPrefix = null)
    {
        return [

        ];
    }

    public function listSetsByToken($token)
    {
        // TODO: Implement listSetsByToken() method.
    }

    public function getRecord($metadataFormat, $identifier)
    {
        // TODO: Implement getRecord() method.
    }

    public function listRecords(
        $metadataFormat = null,
        $set = null,
        $options = []
    ) {

        $records = [];

        $count = 250;
        for ($i=1;$i<=$count;$i++) {
            $record = new Record("oai:id:$i", Carbon::now()->format($this->getDateFormat()));
            $record
                ->setMetadata("<rifcs for='".$i."'>$i</rifcs>");

            $sets = ['1', '2', '3'];
            foreach ($sets as $set) {
                $record->addSet(new Set($set, "Name of $set"));
            }

            $records[] = $record;
        }

        $total = count($records);

        // limit & offset
        $limit = $options['limit'];
        $offset = $options['offset'];

        $records = array_slice($records, $offset, $limit);

        return [
            'total' => $total,
            'records' => $records,
            'limit' => $limit,
            'offset' => $offset
        ];
    }

    public function listRecordsByToken($token)
    {
        // TODO: Implement listRecordsByToken() method.
    }
}