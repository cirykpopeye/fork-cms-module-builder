<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 1:39
 */

namespace Backend\Modules\Builder\Domain\NodeContent;


use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeDataTransferObject;
use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\Builder\Domain\SectionField\SectionField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var NodeContentDataTransferObject $data */
                $data = $event->getData();

                $form = $event->getForm();

                $label = $data->field->getTitle();
                $type = Section::getFieldType($data->field->getType());

                $options = array(
                    'label' => $label
                );


                /** @var NodeDataTransferObject $nodeDataTransferObject */
                $nodeDataTransferObject = $form->getParent()->getParent()->getData();

                $fields = $nodeDataTransferObject->section->getSectionFields();

                /** @var SectionField $field */
                foreach ($fields as $field) {
                    if ($field->getField() == $data->field) {
                        $options['required'] = $field->isRequired();
                        break;
                    }
                }

                if ($data->field->getType() === 'node') {
                    $options['class'] = Node::class;
                }

                $form->add('value', $type, $options);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data_class', NodeContentDataTransferObject::class);
    }
}