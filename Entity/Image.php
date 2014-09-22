<?php

/*
 * This file is part of Manhattan Posts Bundle
 *
 * (c) James Rickard <james@frodosghost.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
