<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentControllerTest extends WebTestCase
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

    public function testDocuments()
    {
        $user = $this->em->getRepository('ManhattanConsoleBundle:User')->find(1);
        $this->loginAs($user, 'secured_area');
        $client = $this->makeClient(true);

        $domain = static::$kernel->getContainer()->getParameter('domain');
        $crawler = $client->request('GET', '/posts/new', array(), array(), array(
            'HTTP_HOST'       => 'console.'. $domain,
            'HTTP_USER_AGENT' => 'Symfony2 BrowserKit'
        ));

        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'post[title]'        => 'Foo',
            'post[excerpt]'      => 'Foo Bar',
            'post[body]'         => 'Foo Bar',
            'post[publishState]' => 2,
            'post[category]'     => 1
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('a:contains("Foo")')->count(), 'New Post is created.');

        // Follow to the Edit page to display link to Documents
        $crawler = $client->click($crawler->filter('a:contains("Foo")')->link());

        $crawler = $client->click($crawler->filter('a:contains("Manage Documents")')->link());

        $this->assertEquals('Documents: Foo', $crawler->filter('h3')->text(),
            'Manage Documents page shows correct heading.');

        $this->assertEquals(0, $crawler->filter('.document-list')->children()->count(),
            'The Document List div is empty becuase nothing has been uploaded.');

        $form = $crawler->selectButton('Add Document')->form(array(
            'document[title]'       => 'Foo',
            'document[description]' => 'Foo Bar'
        ));
        // Test Upload file
        $document = new UploadedFile(
            __FILE__,
            'document.pdf',
            'application/pdf',
            123
        );
        $form['document[file]']->upload($document);

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertEquals(1, $crawler->filter('.document-list')->children()->count(),
            'The Document List has been updated with a single upload.');

        $crawler = $client->click($crawler->selectLink('Edit')->last()->link());

        $form = $crawler->selectButton('Save')->form(array(
            'document[title]' => 'Bar'
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('[value="Bar"]')->count() > 0,
            'Form has been updated.');

        /* Comment included so can return to checking form validation.
         *
           $form = $crawler->selectButton('Add Document')->form(array(
           'document[title]'       => '',
            'document[description]' => ''
        ));

        $client->submit($form);

        $this->assertEquals(1, $crawler->filter('input[id=document_title]')->siblings(),
            'The Document List has been updated with a single upload.');
        */

    }

}
