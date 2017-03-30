<?php


namespace MinhD\OAIPMH;

use GuzzleHttp\Psr7\Response as Psr7Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_return_a_response()
    {
        $response = new Response();
        $this->assertInstanceOf(Psr7Response::class, $response->getResponse());
        $body = $response->getResponse()->getBody()->getContents();
        $this->assertNotEmpty($body);
    }

    /** @test **/
    public function it_should_return_an_identify_response()
    {
        $response = new Response();
        $response->addElement('request', 'http://minhd-oai.com', ['verb' => 'Identify']);
        $body = $response->getResponse()->getBody()->getContents();
        $this->assertContains('request', $body);
        $this->assertContains('Identify', $body);
    }

}
