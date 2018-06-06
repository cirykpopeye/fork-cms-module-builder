<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 0:49
 */

namespace Backend\Modules\Builder\Domain\SectionField;


use Backend\Modules\Builder\Domain\Field\Field;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('field', EntityType::class, array(
            'label' => 'lbl.Field',
            'class' => Field::class
        ));
        $builder->add('required', CheckboxType::class, array(
            'label' => 'lbl.Required',
            'required' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', SectionFieldDataTransferObject::class);
    }
}