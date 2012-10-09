<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use Manhattan\Bundle\ContentBundle\Entity\Asset;
use Manhattan\Bundle\PostsBundle\Entity\Post;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Image
 *
 * @ORM\Table(name="news_image")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Image extends Asset
{
    public function getUploadDir()
    {
        return 'uploads/news';
    }

}