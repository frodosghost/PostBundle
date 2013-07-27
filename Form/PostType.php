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
            ->add('excerpt', 'textarea', array(
                'required' => true,
                'label' => 'Excerpt'
            ))
            ->add('body', 'textarea', array(
                'attr'  => array(
                    'class' => 'tinymce',
                    'data-theme' => 'body'
                ), 'required' => true,
                'label' => 'Body'
            ))
            ->add('publish_state', 'choice', array(
                'choices' => $options['data']->getStaticArray()
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
