<?php

namespace Manhattan\Bundle\PostsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints\Image;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'Image',
                'image_path' => 'webPath',
                "attr" => array(
                    "accept" => "image/*"
                )
            ))
        ;
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
