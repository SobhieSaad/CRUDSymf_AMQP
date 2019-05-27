<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Gherkin\Node\PyStringNode;
/**
 * This context class contains the definitions of the steps used by the demo 
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 * 
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext  implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    private $doctrine;
    private $manager;
    private $schemaTool;
    private $classes;
    /**
     * @var Response|null
     */
    private $response;

    public function __construct(KernelInterface $kernel){
        $this->kernel = $kernel;
        /*$this->doctrine = $doctrine;
        $this->manager = $doctrine->getManager();
        $this->schemaTool = new \Doctrine\ORM\Tools\SchemaTool($this->manager);
        $this->classes = $this->manager->getMetadataFactory()->getAllMetadata();*/
    }

    /**
     * @When a GetUser scenario sends a request to :path
     */
    public function aGetUserScenarioSendsARequestTo(string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, 'GET'));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @When /^I request "([^"]*)" on  "([^"]*)"$/
     */
    public function iRequestOn(string $method,string $path)
    {
        $this->response = $this->kernel->handle(Request::create($path, $method));
    }



    /**
     * @When /^I request "([^"]*)"$/
     */
    public function iRequest($arg1)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }
    /**
     * @Then the response status code should be ok
     */
    public function theResponseStatusCode()
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }


}
