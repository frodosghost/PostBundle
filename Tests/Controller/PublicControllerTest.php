<?php

namespace AGB\Bundle\NewsBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PublicControllerTest extends WebTestCase
{
    /**
     * Tests empty index with no data loaded into display on list page
     */
    public function testEmptyIndex()
    {
        $client = static::createClient();

        $this->loadFixtures(array());

        $crawler = $client->request('GET', '/news');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the News Index page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("There are no news posts")')->count(),
            'Visiting the News Index page with no results displays a small string with no results.');
    }

    /**
     * Populates News Index pageto show test data for list page
     */
    public function testPopulatedIndex()
    {
        $client = static::createClient();

        // Add data with Fixtures to include post listings
        $classes = array(
            'AGB\Bundle\NewsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        );
        $this->loadFixtures($classes);

        $crawler = $client->request('GET', '/news');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the News Index page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('h1:contains("News")')->count(),
            'Visiting the News Index page displays the Header of News');

        /* Click Link of News Item
        $link = $crawler->selectLink('Post Title 2')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the view page from the Index page returns a status code of 200.');*/
    }

    public function testRss()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/news/rss.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the News RSS page returns a status code of 200.');
    }

}