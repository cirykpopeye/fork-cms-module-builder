<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:31
 */

namespace Backend\Modules\Builder\Domain\Section;


use Backend\Modules\Builder\Domain\SectionField\SectionFieldType;
use Common\Form\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, array(
            'label' => 'lbl.Title'
        ))
            ->add('sectionFields', CollectionType::class, array(
                'entry_type' => SectionFieldType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', SectionDataTransferObject::class);
    }
}