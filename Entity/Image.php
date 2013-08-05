<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Manhattan\Bundle\ContentBundle\Entity\Asset;
use Manhattan\Bundle\PostsBundle\Entity\Post;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Image
 */
class Image extends Asset
{
    public function getUploadDir()
    {
        return 'uploads/news';
    }

}
