<?php

namespace AGB\Bundle\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use AGB\Bundle\ContentBundle\Entity\Asset;

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

    public function getUploadDir()
    {
        return 'uploads/news';
    }

}
