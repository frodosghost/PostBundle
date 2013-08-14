<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Manhattan\Bundle\PostsBundle\Entity\Image;
use Manhattan\Bundle\PostsBundle\Entity\Document;
use Manhattan\Bundle\PostsBundle\Entity\Category;
use Manhattan\Bundle\PostsBundle\Entity\Base\Publish;

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
    private $documents;

    /**
     * @var Manhattan\Bundle\PostsBundle\Entity\Image
     */
    private $image;

    /**
     * @var Manhattan\Bundle\PostsBundle\Entity\Category
     **/
    private $category;

    /**
     * @var datetime $createdAt
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     */
    private $updatedAt;


    public function __construct()
    {
        $this->documents = new ArrayCollection();

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

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * PrePersist()
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * PreUpdate()
     */
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTime());
    }

}
