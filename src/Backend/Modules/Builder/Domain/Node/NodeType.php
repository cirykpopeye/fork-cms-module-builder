<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 1:35
 */

namespace Backend\Modules\Builder\Domain\Node;


use Backend\Modules\Builder\Domain\Node\Command\UpdateNode;
use Backend\Modules\Builder\Domain\NodeContent\NodeContentDataTransferObject;
use Backend\Modules\Builder\Domain\NodeContent\NodeContentType;
use Backend\Modules\Builder\Domain\Section\Section;
use Common\Form\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'lbl.Title'
            ))
            ->add('section', EntityType::class, array(
                'class' => Section::class
            ))
            ->add('key', TextType::class, array(
                'label' => 'lbl.Key'
            ))
            ->add('parent', EntityType::class, array(
                'class' => Node::class,
                'required' => false,
                'placeholder' => 'Choose your parent'
            ));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();

                /** @var NodeDataTransferObject $data */
                $data = $event->getData();

                /** @var Section $section */
                $section = $data->section;

                if ($data instanceof UpdateNode) {
                    foreach ($section->getSectionFields() as $field) {
                        $found = false;

                        /** @var NodeContentDataTransferObject $content */
                        foreach ($data->content as $content) {
                            if ($content->field === $field->getField()) {
                                $found = true;
                            }
                        }

                        if (!$found) {
                            $createNodeContent = new NodeContentDataTransferObject();
                            $createNodeContent->field = $field->getField();
                            $createNodeContent->node = $data->getNode();
                            $data->content[] = $createNodeContent;
                        }
                    }
                } else {
                    //-- Create
                    foreach ($section->getSectionFields() as $field) {
                        $createNodeContent = new NodeContentDataTransferObject();
                        $createNodeContent->field = $field->getField();
                        $data->content[] = $createNodeContent;
                    }
                }

                $form->add('content', CollectionType::class, array(
                    'entry_type' => NodeContentType::class
                ));
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => NodeDataTransferObject::class,
            'section' => null
        ));
    }
}