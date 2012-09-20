<?php

namespace AGB\Bundle\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'preview_file', array(
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
            'data_class' => 'AGB\Bundle\NewsBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'image';
    }

}