<?php


namespace MinhD\OAIPMH;

use Carbon\Carbon;
use DOMDocument;
use MinhD\OAIPMH\Exception\BadArgumentException;
use MinhD\OAIPMH\Exception\BadVerbException;
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
                    throw new BadVerbException("Bad Verb");
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
        $error->setAttribute('code', $exception->getErrorName());
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

    private function addResumptionToken(Response $response, $token, $offset = 0, $total = 0)
    {
        $node = $response->addElement('resumptionToken', $token);
        if ($total > 0) {
            $node->setAttribute('cursor', $offset);
            $node->setAttribute('completeListSize', $total);
        }
        return $response;
    }

    private function identify()
    {
        $response = $this->getCommonResponse();
        $identity = $this->repository->identify();

        $identityElement = $response->addElement('Identify');
        foreach ($identity as $key => $value) {
            $node = $response->createElement($key, $value);
            $identityElement->appendChild($node);
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
            $node = $response->createElement('metadataFormat');
            foreach ($value as $k => $v) {
                $node->appendChild(
                    $response->createElement($k, $v)
                );
            }
            $element->appendChild($node);
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
        foreach ($sets['sets'] as $set) {
            $node = $response->createElement('set');
            foreach ($set->toArray() as $k => $v) {
                $node->appendChild(
                    $response->createElement($k, $v)
                );
            }
            $element->appendChild($node);
        }

        // check if there should be more
        // assign resumption token if true
        if (($sets['offset'] + $sets['limit']) < $sets['total']) {
            $resumptionToken = $this->encodeToken(
                [ 'offset' => $sets['offset'] + $sets['limit'] ]
            );
            $response = $this->addResumptionToken($response, $resumptionToken, $sets['offset'], $sets['total']);
        }

        return $response;
    }

    private function listIdentifiers()
    {
        $response = $this->getCommonResponse();

        if (!in_array('metadataPrefix', $this->options)) {
            throw new badArgumentException();
        }

        return $response;
    }

    private function getRecord()
    {
        return new Response();
    }

    private function listRecords()
    {
        $response = $this->getCommonResponse();
        $set = null;

        if (!array_key_exists('metadataPrefix', $this->options)) {
            throw new BadArgumentException("bad argument: Missing required argument 'metadataPrefix'");
        }

        $options = [
            'limit' => $this->limit,
            'set' => null,
            'offset' => 0,
            'from' => null,
            'to' => null
        ];

        if (array_key_exists('set', $this->options)) {
            $options['set'] = $this->options['set'];
        }

        if (array_key_exists('resumptionToken', $this->options)) {
            $data = $this->decodeToken($this->options['resumptionToken']);
            $options = $data;
        }

        $metadataPrefix = $this->options['metadataPrefix'];

        $records = $this->repository->listRecords($metadataPrefix, $set, $options);

        $element = $response->addElement('ListRecords');
        foreach ($records['records'] as $record) {
            $data = $record->toArray();

            $recordNode = $element->appendChild(
                $response->createElement('record')
            );

            $headerNode = $recordNode->appendChild($response->createElement('header'));
            $headerNode
                ->appendChild(
                    $response->createElement('identifier', $data['identifier'])
                );
            $headerNode
                ->appendChild(
                    $response->createElement('datestamp', $data['datestamp'])
                );
            foreach ($data['specs'] as $spec) {
                $headerNode
                    ->appendChild(
                        $response->createElement('setSpec', $spec->getSetSpec())
                    );
            }

            $metadataNode = $recordNode->appendChild($response->createElement('metadata'));

            $fragment = $response->getContent()->createDocumentFragment();
            $fragment->appendXML($data['metadata']);
            $metadataNode->appendChild($fragment);

            $element->appendChild($recordNode);
        }

        // resumptionToken
        if (($records['offset'] + $records['limit']) < $records['total']) {
            $options['offset'] = $records['offset'] + $records['limit'];
            $resumptionToken = $this->encodeToken($options);
            $response = $this->addResumptionToken($response, $resumptionToken, $records['offset'], $records['total']);
        }

        return $response;
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