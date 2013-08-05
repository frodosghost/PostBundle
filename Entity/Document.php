<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

use Manhattan\Bundle\PostsBundle\Entity\Asset;
use Manhattan\Bundle\PostsBundle\Entity\Post;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Document
 *
 * @ORM\Table(name="news_document")
 * @ORM\Entity(repositoryClass="Manhattan\Bundle\PostsBundle\Entity\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document extends Asset
{
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
     * @var text $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="documents")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="cascade")
     */
    private $post;


    public function __toString()
    {
        return $this->getWebPath();
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Photo
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
     * Set description
     *
     * @param text $description
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add post
     *
     * @param Manhattan\Bundle\PostsBundle\Entity\Post $post
     */
    public function addPost(Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * Get post
     *
     * @return Manhattan\Bundle\PostsBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Returns file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return preg_replace('/^.*\./', '', $this->getFilename());;
    }

    public function getUploadDir()
    {
        return 'uploads/documents/'. $this->getPost()->getSlug();
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        $this->setMimeType($this->getFile()->getMimetype());

        // set the path property to the filename where you'ved saved the file
        $filename = $this->sanitise($this->getFile()->getClientOriginalName());
        $this->setFilename($filename);
    }

}
