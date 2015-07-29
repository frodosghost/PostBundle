<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Entity;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class PostRepositoryFunctionalTest extends WebTestCase
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

        // Add data with Fixtures to include post listings
        $this->loadFixtures(array(
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

    public function testOneByDateAndSlug()
    {
        $date = new \DateTime();

        $post = $this->em
            ->getRepository('ManhattanPostsBundle:Post')
            ->findOneByDateAndSlug($date->format('Y-m-d'), 'post-title-1');

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Post', $post, '->findOneByDateAndSlug() returns Post object with query');
    }

    /**
     * Tests finding no documents when item has no documents attached
     */
    public function testFindByIdJoinDocumentsNone()
    {
        $post = $this->em
            ->getRepository('ManhattanPostsBundle:Post')
            ->setPublishState(2)
            ->findOneByIdJoinDocuments(2);

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Post', $post,
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
            ->getRepository('ManhattanPostsBundle:Post')
            ->findOneByIdJoinDocuments(10);

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Post', $post,
            'findOneByIdJoinDocuments() returns Post object with query');

        $this->assertEquals(1, $post->getDocuments()->count(),
            '->getDocuments() returns a count of 1 when one document is attached.');

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Document', $post->getDocuments()->first(),
            '->getDocuments() First element is returned matches the class Document');
    }

}
