<?php

namespace AGB\Bundle\NewsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PostRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PostRepository extends EntityRepository
{
	/**
     * Returns Category with joined Gallery records
     *
     * @param string $slug
     */
    public function findOneByDateAndSlug($date, $slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post, category, image FROM AGBNewsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date BETWEEN :date_start AND :date_end
                    AND post.slug = :slug'
            )->setParameters(array(
            	'date_start' => new \DateTime($date .'00:00:00'),
            	'date_end'   => new \DateTime($date .'23:59:59'),
                'slug'       => $slug
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
                SELECT post, category, image FROM AGBNewsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date < :date
                ORDER BY post.published_date DESC'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME);

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
                SELECT post, category, image FROM AGBNewsBundle:Post post
                LEFT JOIN post.category category
                LEFT JOIN post.image image
                WHERE post.published_date < :date
                    AND category.slug = :category'
            )->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
             ->setParameter('category', $category);

        return $query;
    }

    /**
     * Returns Latest News items
     *
     * @param integer $limit Limit of news items required to be returned
     */
    public function getLatestNews($limit)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT post FROM AGBNewsBundle:Post post
                WHERE post.published_date < :date
                ORDER BY post.published_date DESC')
            ->setParameter('date', new \DateTime(), \Doctrine\DBAL\Types\Type::DATETIME)
            ->setMaxResults($limit);

        try {
            return $query->getResult();
        } catch (\Doctrine\ORM\NoResultException $e) {
            return null;
        }
    }

}