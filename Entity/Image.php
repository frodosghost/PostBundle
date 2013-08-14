<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Manhattan\Bundle\PostsBundle\Entity\Asset;

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
