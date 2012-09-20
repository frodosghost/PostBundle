<?php

namespace AGB\Bundle\NewsBundle\Form;

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
                'required' => false,
                'label' => 'Excerpt'
            ))
            ->add('body', 'textarea', array(
                'attr'  => array(
                    'class' => 'tinymce',
                    'data-theme' => 'body'
                ), 'required' => true,
                'label' => 'Body'
            ))
            ->add('image', new ImageType(), array(
                'data_class' => 'AGB\Bundle\NewsBundle\Entity\Image',
                'widget_control_group' => false,
                'widget_controls' => false
            ))
            ->add('category', 'entity', array(
                'multiple' => false,
                'expanded' => true,
                'class'    => 'AGB\Bundle\NewsBundle\Entity\Category'
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AGB\Bundle\NewsBundle\Entity\Post'
        ));
    }

    public function getName()
    {
        return 'post';
    }
}
