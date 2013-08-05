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
            ->add('file', 'preview_file', array(
                'required' => true,
                'label' => 'Image',
                "attr" => array(
                    "accept" => "image/*"
                ),
                'constraints' => array(
                    new Image(array(
                        'maxSize' => '1024k'
                    ))
                )
        ));
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
