<?php

use MinhD\OAIPMH\Interfaces\OAIRepository;

class SampleRepository implements OAIRepository {

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

    private $sets = [
        [ 'setSpec' => 'testSet1Spec', 'setName' => 'testSet1' ],
        [ 'setSpec' => 'testSet2Spec', 'setName' => 'testSet2' ],
        [ 'setSpec' => 'testSet3Spec', 'setName' => 'testSet3' ],
        [ 'setSpec' => 'testSet4Spec', 'setName' => 'testSet4' ],
        [ 'setSpec' => 'testSet5Spec', 'setName' => 'testSet5' ],
    ];

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

    public function listSets()
    {
        return $this->sets;
    }

    /**
     * @param array $sets
     * @return SampleRepository
     */
    public function setSets($sets)
    {
        $this->sets = $sets;
        return $this;
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