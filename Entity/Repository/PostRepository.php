<?php

namespace Manhattan\Bundle\PostsBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
    // Set to automatically return Published Posts
    private $publishState = 2;

	/**
     * Returns Category with joined Gallery records
     *
     * @param string $slug
     */
    public function findOneByDateAndSlug($date, $slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, category, image, document, user FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                LEFT JOIN post.documents document
                LEFT JOIN post.createdBy user
                WHERE post.publishDate BETWEEN :date_start AND :date_end
                    AND post.slug = :slug
                    AND post.publishState = :publishState'
            )->setParameters(array(
            	'date_start'   => new \DateTime($date .' 00:00:00'),
            	'date_end'     => new \DateTime($date .' 23:59:59'),
                'slug'         => $slug,
                'publishState' => $this->getPublishState()
            ));

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Returns Query joined to Image and Category
     * Includes check of Published Date
     */
    public function getQueryJoinImageAndCategory()
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, category, image, document, user FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                LEFT JOIN post.documents document
                LEFT JOIN post.createdBy user
                WHERE post.publishDate < :date
                    AND post.publishState = :publishState
                ORDER BY post.publishDate DESC'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
             ->setParameter('publishState', $this->getPublishState());

        return $query;
    }

    /**
     * Returns Query joined to Image and Category
     * Includes check of Published Date
     */
    public function getQueryJoinCategory($category)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, category, image, document, user FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                LEFT JOIN post.documents document
                LEFT JOIN post.createdBy user
                WHERE post.publishDate < :date
                    AND post.publishState = :publishState
                    AND category.slug = :category
                ORDER BY post.published_date DESC'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
             ->setParameter('publishState', $this->getPublishState())
             ->setParameter('category', $category);

        return $query;
    }

    /**
     * Returns Latest Post items
     *
     * @param integer $limit Limit of post items required to be returned
     */
    public function findAllLatestPosts($limit)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post FROM ManhattanPostsBundle:Post post
                WHERE post.publishDate < :date
                    AND post.publishState = :publishState
                ORDER BY post.publishDate DESC')
            ->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('publishState', $this->getPublishState())
            ->setMaxResults($limit);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Returns Post with joined Documents
     *
     * @param  int     $id
     * @return Post
     */
    public function findOneByIdJoinDocuments($id)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, document FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.documents document
                WHERE post.id = :id'
            )->setParameters(array(
                'id' => $id
        ));

        try {
            return $query->getSingleResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Sets Publish State to be returned from query
     *
     * @param  int     $publishState
     * @return Post
     */
    public function setPublishState($publishState)
    {
        $this->publishState = $publishState;

        return $this;
    }

    public function getPublishState()
    {
        return $this->publishState;
    }

}
