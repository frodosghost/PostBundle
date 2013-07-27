<?php

namespace AGB\Bundle\NewsBundle\Tests\Entity;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DocumentRepositoryFunctionalTest extends WebTestCase
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

    public function testOneByIdJoinContent()
    {
        $document = $this->em
            ->getRepository('AGBNewsBundle:Document')
            ->findOneByIdJoinPost(1);

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Document', $document,
            'testOneByIdJoinPost() returns Document object with query');

        $this->assertInstanceOf('AGB\Bundle\NewsBundle\Entity\Post', $document->getPost(),
            '->getPost() returns a Post object.');

        $this->assertEquals(10, $document->getPost()->getId(),
            '->getPost() returns the correct Post object.');
    }
}