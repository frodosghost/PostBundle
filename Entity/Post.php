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

use Doctrine\Common\Collections\ArrayCollection;

use Manhattan\Bundle\PostsBundle\Entity\Base\Post as BasePost;
use Manhattan\Bundle\PostsBundle\Entity\Image;
use Manhattan\Bundle\PostsBundle\Entity\Document;
use Manhattan\Bundle\PostsBundle\Entity\Category;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Post
 */
class Post extends BasePost
{

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $documents;

    /**
     * @var Manhattan\Bundle\PostsBundle\Entity\Image
     */
    private $image;

    /**
     * @var Manhattan\Bundle\PostsBundle\Entity\Category
     **/
    private $category;


    public function __construct()
    {
        $this->documents = new ArrayCollection();

        parent::__construct();
    }


    /**
     * Add Document
     *
     * @param Manhattan\Bundle\PostsBundle\Entity\Document $document
     */
    public function addDocument(Document $document)
    {
        $document->addPost($this);
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Get Documents
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Set image
     *
     * @param Manhattan\Bundle\PostsBundle\Entity\Image $image
     * @return Post
     */
    public function setImage(Image $image = null)
    {
        if ($image instanceof Image && $image->hasFile()) {
            $this->image = $image;
            $this->image->preUpload();
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return Manhattan\Bundle\PostsBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add category
     *
     * @param Manhattan\Bundle\PostsBundle\Entity\Category $category
     * @return Post
     */
    public function setCategory(Category $category = null)
    {
        $category->addPost($this);

        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Manhattan\Bundle\PostsBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

}
