<?php

namespace AGB\Bundle\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use AGB\Bundle\ContentBundle\Entity\Asset;
use AGB\Bundle\NewsBundle\Entity\Post;

/**
 * AGB\Bundle\NewsBundle\Entity\Image
 *
 * @ORM\Table(name="news_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image extends Asset
{
    /**
     * @ORM\OneToOne(
     *     targetEntity="Post", cascade={"persist", "remove", "merge"}
     * )
     */
    private $project;

    /**
     * Set project
     *
     * @param AGB\Bundle\NewsBundle\Entity\Post $project
     * @return Image
     */
    public function setProject(Post $project = null)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return AGB\Bundle\NewsBundle\Entity\Post 
     */
    public function getProject()
    {
        return $this->project;
    }

    public function getUploadDir()
    {
        return 'uploads/news';
    }

}