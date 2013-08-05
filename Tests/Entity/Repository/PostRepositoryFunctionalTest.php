<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostRepositoryFunctionalTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()->get('doctrine.orm.entity_manager');

        // Add data with Fixtures to include post listings
        $this->loadFixtures(array(
            'Manhattan\Bundle\PostsBundle\Tests\DataFixtures\ORM\FixtureLoader'
        ));
    }

    protected function tearDown()
    {
        $this->loadFixtures(array());
    }

    public function testOneByDateAndSlug()
    {
        $date = new \DateTime();

        $post = $this->em
            ->getRepository('ManhattanPostsBundle:Post')
            ->findOneByDateAndSlug($date->format('Y-m-d'), 'post-title-1');

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Post', $post, '->findOneByDateAndSlug() returns Post object with query');
    }

}
