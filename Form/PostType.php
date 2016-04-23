<?php

namespace Manhattan\Bundle\PostsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manhattan\Bundle\PostsBundle\Form\EventListener\AddImageFieldSubscriber;

class PostType extends AbstractType
{
    private $class;

    /**
     * @param string $class The Post class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Title'
            ))
            ->add('excerpt', 'markdown', array(
                'show_help' => false
            ))
            ->add('body', 'markdown', array(
                'show_help' => true
            ))
            ->add('image', new ImageType(), array(
                'property_path' => 'image',
                'label' => false
            ))
            ->add('publishState', 'publish_state', array(
                'label' => 'Publish State'
            ))
            ->add('category', 'entity', array(
                'multiple' => false,
                'expanded' => true,
                'class'    => 'ManhattanPostsBundle:Category'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
