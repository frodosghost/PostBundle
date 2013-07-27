<?php

namespace AGB\Bundle\NewsBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentControllerTest extends WebTestCase
{
    public function setUp()
    {
        // Add data with Fixtures to include post listings
        $this->loadFixtures(array(
            'AGB\Bundle\NewsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $this->loadFixtures(array());
    }

    public function testDocuments()
    {
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'test'
        ));

        $crawler = $client->request('GET', '/console/news/new');
        $this->assertTrue(200 === $client->getResponse()->getStatusCode());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'post[title]'         => 'Foo',
            'post[body]'          => 'Foo Bar',
            'post[publish_state]' => 2,
            'post[category]'      => 1,
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertTrue($crawler->filter('td:contains("Foo")')->count() > 0);

        // Follow to the Edit page to display link to Documents
        $crawler = $client->click($crawler->filter('a.btn-primary')->link());

        $crawler = $client->click($crawler->selectLink('Manage Documents')->link());

        $this->assertEquals('Documents: Foo', $crawler->filter('h2')->text(),
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

        $form = $crawler->selectButton('Edit')->form(array(
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
