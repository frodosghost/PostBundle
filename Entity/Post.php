<?php

namespace AGB\Bundle\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

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

}
