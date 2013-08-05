<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

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
    private $publish_state = 2;

	/**
     * Returns Category with joined Gallery records
     *
     * @param string $slug
     */
    public function findOneByDateAndSlug($date, $slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, category, image FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date BETWEEN :date_start AND :date_end
                    AND post.slug = :slug
                    AND post.publish_state = :publish_state'
            )->setParameters(array(
            	'date_start'    => new \DateTime($date .'00:00:00'),
            	'date_end'      => new \DateTime($date .'23:59:59'),
                'slug'          => $slug,
                'publish_state' => $this->getPublishState()
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
                SELECT post, category, image FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date < :date
                    AND post.publish_state = :publish_state
                ORDER BY post.published_date DESC'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
             ->setParameter('publish_state', $this->getPublishState());

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
                SELECT post, category, image FROM ManhattanPostsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date < :date
                    AND post.publish_state = :publish_state
                    AND category.slug = :category'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
             ->setParameter('publish_state', $this->getPublishState())
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
                WHERE post.published_date < :date
                    AND post.publish_state = :publish_state
                ORDER BY post.published_date DESC')
            ->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setParameter('publish_state', $this->getPublishState())
            ->setMaxResults($limit);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

    /**
     * Sets Publish State to be returned from query
     * 
     * @param  int     $publish_state
     * @return Content
     */
    public function setPublishState($publish_state)
    {
        $this->publish_state = $publish_state;

        return $this;
    }

    public function getPublishState()
    {
        return $this->publish_state;
    }

}