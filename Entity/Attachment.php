<?php

namespace Manhattan\Bundle\PostsBundle\Entity;

use Manhattan\Bundle\PostsBundle\Entity\Asset;
use Manhattan\Bundle\PostsBundle\Entity\Post;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Attachment
 */
class Attachment extends Asset
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var text $description
     */
    private $description;

    /**
     * @var Manhattan\Bundle\PostsBundle\Entity\Post
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

    public function getUploadDir()
    {
        return 'uploads/attachments';
    }

    /**
     * PrePersist()
     */
    public function preUpload()
    {
        if (null === $this->getFile()) {
            return;
        }

        try {
            $this->setMimeType($this->getFile()->getMimetype());
        } catch (FileNotFoundException $e) {
            $this->setMimeType($this->getFile()->getClientMimeType());
        }

        // set the path property to the filename where you'ved saved the file
        $filename = $this->sanitise($this->getFile()->getClientOriginalName());
        $this->setFilename($filename);
    }

}
