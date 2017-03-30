<?php

use MinhD\OAIPMH\Interfaces\OAIRepository;

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
            $sets[] = [
                'setSpec' => "testSet{$i}Spec", 'setName' => "testSet{$i}"
            ];
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
}