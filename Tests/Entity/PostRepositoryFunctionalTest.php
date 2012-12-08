<?php

namespace AGB\Bundle\ContentBundle\Tests\Entity;

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
            'AGB\Bundle\NewsBundle\Tests\DataFixtures\ORM\FixtureLoader'
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
            ->getRepository('AGBNewsBundle:Post')
            ->findOneByDateAndSlug($date->format('Y-m-d'), 'post-title-1');

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Post', $post,
            'findOneByDateAndSlug() returns Post object with query');
    }

    /**
     * Tests finding no documents when item has no documents attached
     */
    public function testFindByIdJoinDocumentsNone()
    {
        $post = $this->em
            ->getRepository('AGBNewsBundle:Post')
            ->setPublishState(2)
            ->findOneByIdJoinDocuments(2);

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Post', $post,
            'findOneByIdJoinDocuments() returns Post object with query');

        $this->assertEquals(0, $post->getDocuments()->count(),
            '->getDocuments() returns a count of 0 with no documents attached.');
    }

    /**
     * Tests Finding Document as joined object with one database entry
     */
    public function testFindByIdJoinDocumentsOne()
    {
        $post = $this->em
            ->getRepository('AGBNewsBundle:Post')
            ->findOneByIdJoinDocuments(10);

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Post', $post,
            'findOneByIdJoinDocuments() returns Post object with query');

        $this->assertEquals(1, $post->getDocuments()->count(),
            '->getDocuments() returns a count of 1 when one document is attached.');

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Document', $post->getDocuments()->first(),
            '->getDocuments() First element is returned matches the class Document');
    }

}
