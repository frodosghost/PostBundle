<?php

namespace Manhattan\Bundle\PostsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'preview_file', array(
                'data_class' => null, // http://j.mp/ULMa2k
                'required' => true,
                'label' => 'Image',
                "attr" => array(
                    "accept" => "image/*"
                )
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\PostsBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'image';
    }

}