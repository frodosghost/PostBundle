<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $this->em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->postFixtureSetup();

        // Add data with Fixtures
        $this->loadFixtures(array(
            'Manhattan\Bundle\ConsoleBundle\Tests\DataFixtures\ORM\LoadAuthenticatedAdminUserData',
            'Manhattan\Bundle\PostsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropDatabase();
        $this->postFixtureSetup();

        $this->getContainer()->get('doctrine')->getConnection()->close();
        parent::tearDown();
    }

    public function testCompleteScenario()
    {
        $this->getContainer()->set('manhattan_posts.include_documents', true);

        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        $domain = static::$kernel->getContainer()->getParameter('domain');
        $crawler = $client->request('GET', '/posts', array(), array(), array(
            'HTTP_HOST'       => 'console.'. $domain,
            'HTTP_USER_AGENT' => 'Symfony2 BrowserKit'
        ));

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());
        $crawler = $client->click($crawler->selectLink('Create a New Post')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'post[title]'        => 'Foo',
            'post[excerpt]'      => 'Foo Bar',
            'post[body]'         => 'Foo Bar',
            'post[publishState]' => 2,
            'post[category]'     => 1,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('a:contains("Foo")')->count(), 'New Post is created.');

        // Edit the entity
        $crawler = $client->click($crawler->filter('a:contains("Foo")')->last()->link());

        $form = $crawler->selectButton('Save and Update')->form(array(
            'post[title]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertTrue($crawler->filter('[value="Foo"]')->count() > 0);

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

}
