<?php


namespace MinhD\OAIPMH\TestModel;

use MinhD\OAIPMH\Interfaces\OAIRepository;

class SampleRepository implements OAIRepository {

    private $identity = [
        'repositoryName' => 'Sample Name',
        'baseUrl' => 'http://localhost.com',
        'protocolVersion' => '2.0',
        'earliestDatestamp' => '2010-01-12T05:03:58Z',
        'deletedRecord' => 'transient',
        'granularity' => 'YYYY-MM-DDThh:mm:ssZ',
        'adminEmail' => 'admin@localhost.com'
    ];

    private $sets = [
        [ 'name' => 'testSet1', 'spec' => 'testSet1Spec' ],
        [ 'name' => 'testSet2', 'spec' => 'testSet2Spec' ],
        [ 'name' => 'testSet3', 'spec' => 'testSet3Spec' ],
        [ 'name' => 'testSet4', 'spec' => 'testSet4Spec' ],
        [ 'name' => 'testSet5', 'spec' => 'testSet5Spec' ],
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
}