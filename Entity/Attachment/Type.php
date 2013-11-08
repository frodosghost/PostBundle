<?php

namespace Manhattan\Bundle\PostsBundle\Entity\Attachment;

/**
 * Manhattan\Bundle\PostsBundle\Entity\Attachment\Type
 */
class Type
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
     * @var boolean $hasFile
     */
    private $hasFile;


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
     * @return Type
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
     * @return Type
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
     * Set hasFile
     *
     * @param string $hasFile
     * @return Type
     */
    public function setHasFile($hasFile)
    {
        $this->hasFile = $hasFile;
        return $this;
    }

    /**
     * Get hasFile
     *
     * @return boolean
     */
    public function getHasFile()
    {
        return $this->hasFile;
    }

}
