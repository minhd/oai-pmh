<?php


namespace MinhD\OAIPMH;
use \Exception as Exception;

class Response
{
    private $status = 200;
    private $headers = ['Content-Type' => 'text/xml; charset=utf8'];
    private $content = null;
    private $pretty = true;

    /**
     * OAIResponse constructor.
     */
    public function __construct()
    {
        $this->content = new \SimpleXMLElement('<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" 
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd"/>');
    }

    /**
     * @param $name
     * @param string $value
     * @return \SimpleXMLElement
     */
    public function addElement($name, $value = null, $attributes = [])
    {
        $element = $this->content->addChild($name, $value);
        if (count($attributes) == 0) {
            return $element;
        }

        foreach ($attributes as $key => $value) {
            $element->addAttribute($key, $value);
        }

        return $element;
    }

    /**
     * @param string $name
     * @param \DOMDocument|string $value
     * @return \DOMElement
     */
    public function createElement($name, $value = null)
    {
        $nameSpace = 'http://www.openarchives.org/OAI/2.0/';
        $element = $this->content->createElementNS($nameSpace, $name, htmlspecialchars($value, ENT_XML1));
        return $element;
    }

    public function getResponse()
    {
        $xml = $this->content->saveXML();

        if ($this->pretty) {
            $dom = new \DOMDocument("1.0");
            $dom->preserveWhiteSpace = false;
            $dom->formatOutput = true;
            $dom->loadXML($this->content->saveXML());
            $xml = $dom->saveXML();
        }

        return new \GuzzleHttp\Psr7\Response(
            $this->status,
            $this->headers,
            $xml
        );
    }
}