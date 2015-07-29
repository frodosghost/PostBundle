<?php

namespace Manhattan\Bundle\PostsBundle\Tests\Entity\Repository;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class DocumentRepositoryFunctionalTest extends WebTestCase
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

    public function testOneByIdJoinContent()
    {
        $document = $this->em
            ->getRepository('ManhattanPostsBundle:Document')
            ->findOneByIdJoinPost(1);

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Document', $document,
            'testOneByIdJoinPost() returns Document object with query');

        $this->assertInstanceOf('Manhattan\Bundle\PostsBundle\Entity\Post', $document->getPost(),
            '->getPost() returns a Post object.');

        $this->assertEquals(10, $document->getPost()->getId(),
            '->getPost() returns the correct Post object.');
    }
}
