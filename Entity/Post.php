<?php

namespace AGB\Bundle\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use AGB\Bundle\NewsBundle\Entity\Image;
use AGB\Bundle\NewsBundle\Entity\Category;

/**
 * AGB\Bundle\NewsBundle\Entity\Post
 *
 * @ORM\Table(name="post")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="AGB\Bundle\NewsBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(
     *     message = "Please enter a Title."
     * )
     */
    private $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var text $excerpt
     *
     * @ORM\Column(name="excerpt", type="string", length=500, nullable=true)
     */
    private $excerpt;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Image", cascade={"persist", "remove", "merge"}
     * )
     */
    private $image;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="Category", inversedBy="posts"
     * )
     * @ORM\JoinTable(name="post_map_category")
     **/
    private $categories;

    /**
     * @var datetime $published_date
     *
     * @ORM\Column(name="published_date", type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $published_date;

    /**
     * @var datetime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     *
     * @ORM\Column(name="updated_at", type="datetime")
     * @Assert\Type("\DateTime")
     */
    private $updated_at;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
     * Set published_date
     *
     * @param datetime $publishedDate
     * @return Post
     */
    public function setPublishedDate($publishedDate)
    {
        $this->published_date = $publishedDate;
        return $this;
    }

    /**
     * Get published_date
     *
     * @return datetime 
     */
    public function getPublishedDate()
    {
        return $this->published_date;
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * Get created_at
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set image
     *
     * @param AGB\Bundle\NewsBundle\Entity\Image $image
     * @return Post
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     *
     * @return AGB\Bundle\NewsBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add categories
     *
     * @param AGB\Bundle\NewsBundle\Entity\Category $categories
     * @return Post
     */
    public function addCategory(Category $categories)
    {
        $this->categories[] = $categories;
        return $this;
    }

    /**
     * Get categories
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $date = new \DateTime();
        $date->modify('+1 week');

        $this->setPublishDate($date);
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTime());
    }

}
