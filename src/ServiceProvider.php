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
    private $limit = 100;

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

    /**
     * Sanitize the options provided
     * Remove all unneeded request param
     */
    private function sanitizeOptions()
    {
        // get the verb
        $verb = null;
        if (array_key_exists('verb', $this->options)) {
            $verb = $this->options['verb'];
        }

        // continue if verb is not found
        // probably throw exception here
        $validVerbs = array_keys(self::$validVerbs);
        if (!in_array($verb, $validVerbs)) {
            return;
        }

        // unset all other values other than the needed
        $valid = self::$validVerbs[$verb];
        foreach ($this->options as $key => $value) {
            if ($key == "verb" || in_array($key, $valid)) {
                continue;
            }
            unset($this->options[$key]);
        }
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

    private function addResumptionToken($response, $token)
    {
        $response->addElement('resumptionToken', $token);
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

        $offset = 0;

        if (array_key_exists('resumptionToken', $this->options)) {
            $data = $this->decodeToken($this->options['resumptionToken']);
            $offset = $data['offset'];
        }

        $sets = $this->repository->listSets($this->limit, $offset);

        $element = $response->addElement('ListSets');
        foreach ($sets['sets'] as $key => $value) {
            $format = $element->addChild('set');
            foreach ($value as $k => $v) {
                $format->addChild($k, $v);
            }
        }

        // check if there should be more
        // assign resumption token if true
        if (($sets['offset'] + $sets['limit']) < $sets['total']) {
            $resumptionToken = $this->encodeToken(
                [ 'offset' => $sets['offset'] + $sets['limit'] ]
            );
            $response = $this->addResumptionToken($response, $resumptionToken);
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

    private function encodeToken($data)
    {
        return base64_encode(json_encode($data, true));
    }

    private function decodeToken($data)
    {
        return json_decode(base64_decode($data), true);
    }

}