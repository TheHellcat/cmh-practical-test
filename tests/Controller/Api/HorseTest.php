<?php

namespace App\tests\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use App\DataFixtures\TestFixtures;

class HorseTest extends WebTestCase
{
    /**
     * @var ORMExecutor
     */
    private $fixtureExecutor;

    /**
     * @var ContainerAwareLoader
     */
    private $fixtureLoader;

    /**
     * Test calling GET /horses for retrieving all horses in DB
     */
    public function testGet(): void
    {
        $client = self::createClient();

        // test for proper "auth error" without any auth token
        $client->request('GET', '/horses', [], [], []);
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('not authenticated', $responseContent->status);

        // test for proper "auth error" with invalid auth token
        $client->request('GET', '/horses', [], [], ['HTTP_X_API_Token' => 'INVALIDTOKEN']);
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('not authenticated', $responseContent->status);

        // test if can properly call the "get all" endpoint
        $client->request('GET', '/horses', [], [], ['HTTP_X_API_Token' => 'TEST00000']);
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('ok', $responseContent->status);
        $this->assertEquals(5, count($responseContent->horses));
        $this->assertEquals('Testhorse 1', $responseContent->horses[1]->name);
        $this->assertEquals('Testhorse 3', $responseContent->horses[3]->name);
    }

    /**
     * Test calling POST /horses to add a horse
     */
    public function testPost(): void
    {
        $client = self::createClient();

        // test for proper "auth error" without any auth token
        $client->request('POST', '/horses', [], [], [], '{"name"="Testhorse 5","picture"="http://test.tst/test5.jpg"');
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('not authenticated', $responseContent->status);

        // test if can properly call the "add horse" endpoint
        $client->request('POST', '/horses', [], [], ['HTTP_X_API_Token' => 'TEST00000'], '{"name":"Testhorse 5","picture":"http://test.tst/test5.jpg"}');
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('success', $responseContent->status);
        $this->assertEquals('Entry added', $responseContent->message);

        // check we actually have one horse more in the DB now
        $client->request('GET', '/horses', [], [], ['HTTP_X_API_Token' => 'TEST00000']);
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals(6, count($responseContent->horses));

        // test if invalid JSON is handled properly
        $client->request('POST', '/horses', [], [], ['HTTP_X_API_Token' => 'TEST00000'], '{"name""Testhorse 6","picture""http://test.tst/test6.jpg"}');
        $responseContent = json_decode($client->getResponse()->getContent());
        $this->assertEquals('error', $responseContent->status);
        $this->assertContains('malformed JSON', $responseContent->message);
    }

    /**
     * Setup stuff needed by the actual tests
     */
    public function setUp(): void
    {
        self::bootKernel();
        $this->addFixture(new TestFixtures());
        $this->executeFixtures();
    }

    /*  vv  helper functions to execute data fixtures programmatically during runtime  vv  */
    /*      instead of having to load them manually from the command line                  */

    /**
     * Adds a new fixture to be loaded.
     *
     * @param FixtureInterface $fixture
     */
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->getFixtureLoader()->addFixture($fixture);
    }

    /**
     * Executes all the fixtures that have been loaded so far.
     */
    protected function executeFixtures()
    {
        $this->getFixtureExecutor()->execute($this->getFixtureLoader()->getFixtures());
    }

    /**
     * @return ORMExecutor
     */
    private function getFixtureExecutor()
    {
        if (!$this->fixtureExecutor) {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = self::$kernel->getContainer()->get('doctrine')->getManager();
            $this->fixtureExecutor = new ORMExecutor($entityManager, new ORMPurger($entityManager));
        }
        return $this->fixtureExecutor;
    }

    /**
     * @return ContainerAwareLoader
     */
    private function getFixtureLoader()
    {
        if (!$this->fixtureLoader) {
            $this->fixtureLoader = new ContainerAwareLoader(self::$kernel->getContainer());
        }
        return $this->fixtureLoader;
    }
}
