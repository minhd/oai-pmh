<?php


use MinhD\OAIPMH\OAIServiceProvider;

class OAIServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_should_construct_a_provider()
    {
        $provider = $this->getTestProvider();
        $this->assertInstanceOf(OAIServiceProvider::class, $provider);
    }

    /** @test **/
    public function it_should_set_options()
    {
        $provider = $this->getTestProvider();
        $options = [
            'verb' => 'Identify'
        ];
        $provider->setOptions($options);
        $this->assertEquals($options, $provider->getOptions());
    }

    /** @test **/
    public function it_should_identify()
    {
        $provider = $this->getTestProvider();
        $response = $provider->setOption('verb', 'Identify')->get()->getResponse()->getBody()->getContents();
        dd($response);
    }

    private function getTestProvider()
    {
        return new OAIServiceProvider();
    }
}
