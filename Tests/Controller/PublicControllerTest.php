<?php

namespace AGB\Bundle\NewsBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{

    public function testIndex()
    {
        $client = static::createClient();

        $classes = array(
            'AGB\Bundle\NewsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        );
        $this->loadFixtures($classes);

        $crawler = $client->request('GET', '/news');

        var_dump($client->getResponse());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the News Index page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('h1:contains("News")')->count(),
            'Visiting the News Index page displays the Header of News');

        // Click Link of News Item
        $link = $crawler->selectLink('Post Title 2')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the view page from the Index page returns a status code of 200.');
    }
}