<?php

namespace Backend\Modules\Builder\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Engine\Model;
use Backend\Modules\Builder\Domain\Field\Command\CreateField;
use Backend\Modules\Builder\Domain\Field\FieldType;
use Backend\Modules\Builder\Domain\Node\Command\CreateNode;
use Backend\Modules\Builder\Domain\Node\NodeType;
use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Command\CreateContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockType;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Event\ContentBlockCreated;
use Doctrine\Common\Util\Debug;
use Symfony\Component\Form\Form;

/**
 * This is the add-action, it will display a form to create a new item
 */
class AddNode extends BackendBaseActionAdd
{
    public function execute(): void
    {
        parent::execute();
        $this->header->addJS('Node.js');

        $sections = $this->get('builder.repository.section')->findAll();
        $section = reset($sections);

        $createNode = new CreateNode();
        $createNode->section = $section;

        $form = $this->getForm(
            $createNode
        );

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());

            $this->parse();
            $this->display();

            return;
        }

        $createNode = $this->createNode($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'added',
                    'var' => $createNode->title,
                ]
            )
        );
    }

    private function createNode(Form $form): CreateNode
    {
        $createNode = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($createNode);

        return $createNode;
    }

    private function getBackLink(array $parameters = []): string
    {
        return BackendModel::createUrlForAction(
            'Nodes',
            null,
            null,
            $parameters
        );
    }

    private function getForm(CreateNode $createNode): Form
    {
        /** @var Form $form */
        $form = $this->get('builder.helper.field')->createForm(
            NodeType::class,
            $createNode
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }
}
