<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:31
 */

namespace Backend\Modules\Builder\Domain\Field;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldType extends AbstractType
{
    const TYPE_CHOICES = array(
        'text' => 'Text',
        'textarea' => 'Text area',
        'editor' => 'Editor',
        'node' => 'Node',
        'image' => 'Image'
    );

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'lbl.Title'
        ))
            ->add('key', TextType::class, array(
                'label' => 'lbl.Key'
            ))
            ->add('type', ChoiceType::class, array(
                'choices' => array_flip(self::TYPE_CHOICES),
                'label' => 'lbl.Type'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', FieldDataTransferObject::class);
    }
}