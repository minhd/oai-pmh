<?php


namespace MinhD\OAIPMH;

class ServiceProvider
{
    protected static $validVerbs = [
        "Identify" => [],
        "ListMetadataFormats" => ['identifier'],
        "ListSets" => ['resumptionToken'],
        "GetRecord" => ['identifier', 'metadataPrefix'],
        "ListIdentifiers" => ['from', 'until', 'metadataPrefix', 'set', 'resumptionToken'],
        "ListRecords" => ['from', 'until', 'metadataPrefix', 'set', 'resumptionToken']
    ];

    private $options = [];

    /**
     * OAIServiceProvider constructor.
     */
    public function __construct()
    {

    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     *
     */
    public function get()
    {
        if (!array_key_exists('verb', $this->options)) {
            // exception
        }

        switch ($this->options['verb']) {
            case "Identify":
                return $this->identify();
                break;
            case "ListMetadataFormats":
                return $this->listMetadataFormats();
                break;
            case "ListSets":
                return $this->listSets();
                break;
            case "ListRecords":
                return $this->listRecords();
                break;
            case "ListIdentifiers":
                return $this->listIdentifiers();
                break;
            case "GetRecord":
                return $this->getRecord();
                break;
            default:
                // exception badverb
                return null;
        }
    }

    private function identify()
    {
        return new Response();
    }

    private function listMetadataFormats()
    {
        return new Response();
    }

    private function listSets()
    {
        return new Response();
    }

    private function listIdentifiers()
    {
        return new Response();
    }

    private function getRecord()
    {
        return new Response();
    }

    private function listRecords()
    {
        return new Response();
    }

}