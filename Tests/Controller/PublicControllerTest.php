<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Controller;

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

        $crawler = $client->request('GET', '/articles');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the Articles Index page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("There are no posts")')->count(),
            'Visiting the Articles Index page with no results displays a small string with no results.');
    }

    /**
     * Populates Articles Index pageto show test data for list page
     */
    public function testPopulatedIndex()
    {
        $client = static::createClient();

        // Add data with Fixtures to include post listings
        $classes = array(
            'Manhattan\Bundle\PostsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        );
        $this->loadFixtures($classes);

        $crawler = $client->request('GET', '/articles');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the Articles Index page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('h1:contains("Articles")')->count(),
            'Visiting the Articles Index page displays the Header of Articles');

        /* Click Link of Articles Item
        $link = $crawler->selectLink('Post Title 2')->link();
        $crawler = $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the view page from the Index page returns a status code of 200.');*/
    }

    /**
     * Tests the RSS page with loaded data.
     */
    public function testRss()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/articles/rss.xml');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            'Visiting the Articles RSS page returns a status code of 200.');

        $this->assertGreaterThan(0, $crawler->filter('rss')->count(),
            'The RSS page generates xml with an rss element.');

        // Count the item tags from the fixtures data
        $this->assertEquals(5, $crawler->filter('item')->count(),
            'Displays the 5 Published Items in the news data.');
    }

}