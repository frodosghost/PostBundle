<?php

namespace Manhattan\Bundle\PostsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
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
            ->add('publishState', 'publish_state', array(
                'label' => 'Publish State'
            ))
            ->add('image', new ImageType(), array(
                'data_class' => 'Manhattan\Bundle\PostsBundle\Entity\Image',
                'widget_control_group' => false,
                'label' => false
            ))
            ->add('category', 'entity', array(
                'multiple' => false,
                'expanded' => true,
                'class'    => 'Manhattan\Bundle\PostsBundle\Entity\Category'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\PostsBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
