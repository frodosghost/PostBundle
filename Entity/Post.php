<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

use Manhattan\Bundle\PostsBundle\Entity\Image;
use Manhattan\Bundle\PostsBundle\Entity\Category;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Post
 *
 * @ORM\Table(name="news_post")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Manhattan\Bundle\PostsBundle\Entity\PostRepository")
 */
class Post
{
    /**
     * Publish States
     *
     * @link(Bitwise Operators, http://php.net/manual/en/language.operators.bitwise.php)
     */
    const DRAFT = 1;

    const PUBLISH = 2;

    const ARCHIVE = 4;

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
     *     message = "Please enter a Title"
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
     * @Assert\MaxLength(500)(
     *     message="The Excerpt is too long. The is a maximum length of {{limit}} characters"
     * )
     */
    private $excerpt;

    /**
     * @var text $body
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     * @Assert\NotBlank(
     *     message = "Please enter some text for the body"
     * )
     */
    private $body;

    /**
     * @ORM\OneToOne(
     *     targetEntity="Image", cascade={"persist", "remove", "merge"}
     * )
     */
    private $image;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="Category", inversedBy="posts"
     * )
     **/
    private $category;

    /**
     * @ORM\OneToMany(
     *     targetEntity="Manhattan\Bundle\PostsBundle\Entity\Document", mappedBy="post", cascade={"persist", "remove"}
     * )
     */
    private $documents;

    /**
     * @var datetime $published_date
     *
     * @ORM\Column(name="published_date", type="datetime", nullable=true)
     * @Assert\Type("\DateTime")
     */
    private $published_date;

    /**
     * var integer $publish_state
     *
     * @ORM\Column(name="publish_state", type="integer")
     */
    private $publish_state;

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
        $this->category = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->publish_state = 1;
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
     * Set published_date
     *
     * @param datetime $publishedDate
     * @return Post
     */
    public function setPublishDate($publishedDate)
    {
        $this->published_date = $publishedDate;
        return $this;
    }

    /**
     * Get published_date
     *
     * @return datetime
     */
    public function getPublishDate()
    {
        return $this->published_date;
    }

    /**
     * Returns format for URL
     */
    public function getDate()
    {
        $this->date = $this->published_date->format('Y-m-d');
        return $this->date;
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
     * @param Manhattan\Bundle\PostsBundle\Entity\Image $image
     * @return Post
     */
    public function setImage(Image $image = null)
    {
        if ($image instanceof Image && $image->hasFile())
        {
            $this->image = $image;
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
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set publish_state
     *
     * @param integer $publish_state
     */
    public function setPublishState($publish_state)
    {
        $this->publish_state = $publish_state;

        if ($publish_state === self::PUBLISH) {
            $this->setPublishDate(new \DateTime());
        }

        return $this;
    }

    /**
     * Get publish_state
     *
     * @return integer
     */
    public function getPublishState()
    {
        return $this->publish_state;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Returns array of static values for configuring form select values
     *
     * @return srray
     */
    public function getStaticArray()
    {
        return array(
            self::DRAFT => 'Draft',
            self::PUBLISH => 'Publish',
            self::ARCHIVE => 'Archive'
        );
    }

}
