<?php


namespace MinhD\OAIPMH;


use GuzzleHttp\Psr7\Response;

class OAIResponse
{
    private $status = 200;
    private $headers = ['Content-Type' => 'text/xml; charset=utf8'];
    private $content = null;

    /**
     * OAIResponse constructor.
     */
    public function __construct()
    {
        $this->content = new \DOMDocument('1.0', 'UTF-8');
        $this->content->formatOutput = true;
        $documentElement = $this->content->createElementNS('http://www.openarchives.org/OAI/2.0/', "OAI-PMH");
        $documentElement->setAttribute('xmlns', 'http://www.openarchives.org/OAI/2.0/');
        $documentElement->setAttributeNS(
            "http://www.w3.org/2001/XMLSchema-instance",
            'xsi:schemaLocation',
            'http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd'
        );
        $this->content->appendChild($documentElement);
    }

    public function getResponse()
    {
        return new Response(
            $this->status,
            $this->headers,
            $this->content->saveXML()
        );
    }
}