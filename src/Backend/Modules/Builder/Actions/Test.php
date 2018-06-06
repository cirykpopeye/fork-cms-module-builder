<?php

namespace Backend\Modules\Builder\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Modules\Builder\Domain\NodeContent\NodeContent;
use Backend\Modules\Builder\Domain\Section\Section;
use Doctrine\Common\Util\Debug;
use Backend\Modules\Builder\Domain\Node\Node;
use Symfony\Component\Form\Form;

/**
 * This is the index-action (default), it will display the overview
 */
class Test extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        /** @var Section $section */
        $section = $this->get('builder.repository.section')->find(1);

        /** @var Form $form */
        $form = $section->getForm();
        $form->handleRequest($this->getRequest());

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());

            $this->parse();
            $this->display();

            return;
        }

        print "<pre>";

        //-- Create a new node
        $node = new Node();
        $node->setSection($section);

        foreach ($form->all() as $key => $formfield) {
            //-- Get field by key
            $field = $this->get('builder.repository.field')->findOneByKey($key);

            $nodeContent = new NodeContent();
            $nodeContent->setField($field);
            $nodeContent->setNode($node);
            $nodeContent->setValue($formfield->getData());

            $node->addContent($nodeContent);
        }

        $this->get('doctrine.orm.entity_manager')->persist($node);
        $this->get('doctrine.orm.entity_manager')->flush();
    }
}
