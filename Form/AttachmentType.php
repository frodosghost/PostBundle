<?php

namespace Manhattan\Bundle\PostsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Manhattan\Bundle\PostsBundle\Form\EventListener\AddFileFieldSubscriber;

class AttachmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'required' => true
            ))
            ->add('type', 'entity', array(
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'class'    => 'Manhattan\Bundle\PostsBundle\Entity\Attachment\Type'
            ))
            ->add('description', 'textarea', array(
                'required' => true,
                'label' => 'Description'
            ))
        ;

        $subscriber = new AddFileFieldSubscriber($builder->getFormFactory());
        $builder->addEventSubscriber($subscriber);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Manhattan\Bundle\PostsBundle\Entity\Attachment'
        ));
    }

    public function getName()
    {
        return 'document';
    }
}
