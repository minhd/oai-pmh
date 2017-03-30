<?php


namespace MinhD\OAIPMH;

use Carbon\Carbon;
use MinhD\OAIPMH\Interfaces\OAIRepository;

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
    private $repository;

    /**
     * OAIServiceProvider constructor.
     * @param OAIRepository $repository
     */
    public function __construct(OAIRepository $repository)
    {
        $this->repository = $repository;
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
        $this->sanitizeOptions();
        return $this;
    }

    private function sanitizeOptions()
    {
        // TODO
    }

    /**
     *
     */
    public function get()
    {

        $verb = null;
        if (array_key_exists('verb', $this->options)) {
            $verb = $this->options['verb'];
        }

        try {
            switch ($verb) {
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
                    throw new OAIException("badVerb", "Bad verb");
                    break;
            }
        } catch (OAIException $e) {
            return $this->getExceptionResponse($e);
        }

    }

    /**
     * @param OAIException $exception
     * @return Response
     */
    public function getExceptionResponse(OAIException $exception)
    {
        $response = $this->getCommonResponse();
        $error = $response->addElement('error', $exception->getMessage());
        $error->addAttribute('code', $exception->getOaiErrorCode());
        return $response;
    }

    private function getCommonResponse()
    {
        $response = new Response;

        $format = $this->repository->getDateFormat();
        $response->addElement('responseDate', Carbon::now()->format($format));

        $url = "http://$_SERVER[HTTP_HOST]";
        $response->addElement('request', $url, $this->options);

        return $response;
    }

    private function identify()
    {
        $response = $this->getCommonResponse();
        $identity = $this->repository->identify();

        $identityElement = $response->addElement('Identify');
        foreach ($identity as $key => $value) {
            $identityElement->addChild($key, $value);
        }
        return $response;
    }

    private function listMetadataFormats()
    {
        $response = $this->getCommonResponse();

        // TODO identifier
        $metadataFormats = $this->repository->listMetadataFormats();

        $element = $response->addElement("ListMetadataFormats");
        foreach ($metadataFormats as $key => $value) {
            $format = $element->addChild('metadataFormat');
            foreach ($value as $k => $v) {
                $format->addChild($k, $v);
            }
        }

        return $response;
    }

    private function listSets()
    {
        $response = $this->getCommonResponse();

        // TODO resumptionToken
        $sets = $this->repository->listSets();

        $element = $response->addElement('ListSets');
        foreach ($sets as $key => $value) {
            $format = $element->addChild('set');
            foreach ($value as $k => $v) {
                $format->addChild($k, $v);
            }
        }

        return $response;
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