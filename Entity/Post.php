<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Manhattan\PublishBundle\Entity\Publish;
use Manhattan\Bundle\PostsBundle\Entity\Image;
use Manhattan\Bundle\PostsBundle\Entity\Attachment;
use Manhattan\Bundle\PostsBundle\Entity\Category;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Post
 */
class Post extends Publish
{

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var text $excerpt
     */
    private $excerpt;

    /**
     * @var text $body
     */
    private $body;

    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $attachments;

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
        $this->attachments = new ArrayCollection();

        parent::__construct();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Post
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     * @return Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set body
     *
     * @param text $body
     * @return Post
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return text
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Add Attachment
     *
     * @param Manhattan\Bundle\PostsBundle\Entity\Attachment $attachment
     */
    public function addAttachment(Attachment $attachment)
    {
        $attachment->addPost($this);
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Get Attachments
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getAttachments()
    {
        return $this->attachments;
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
