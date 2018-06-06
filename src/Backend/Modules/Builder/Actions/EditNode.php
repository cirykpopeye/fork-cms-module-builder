<?php

namespace Backend\Modules\Builder\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Core\Language\Locale;
use Backend\Form\Type\DeleteType;
use Backend\Modules\Builder\Domain\Field\Command\UpdateField;
use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Field\FieldRepository;
use Backend\Modules\Builder\Domain\Field\FieldType;
use Backend\Modules\Builder\Domain\Node\Command\UpdateNode;
use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeRepository;
use Backend\Modules\Builder\Domain\Node\NodeType;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Command\UpdateContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRepository;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockRevisionDataGrid;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockType;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Event\ContentBlockUpdated;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Exception\ContentBlockNotFound;
use Symfony\Component\Form\Form;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * This is the edit-action, it will display a form to edit an existing item
 */
class EditNode extends BackendBaseActionEdit
{
    public function execute(): void
    {
        parent::execute();
        $this->header->addJS('Node.js');

        $node = $this->getNode();

        $this->header->addJsData($this->getModule(), 'nodeId', $node->getId());

        $form = $this->getForm(new UpdateNode($node));

        $deleteForm = $this->createForm(
            DeleteType::class,
            ['id' => $node->getId()],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());
            $this->template->assign('node', $node);

            $this->parse();
            $this->display();

            return;
        }

        /** @var UpdateNode $updateNode */
        $updateNode = $this->updateNode($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'edited',
                    'var' => $updateNode->title,
                    'highlight' => 'row-' . $node->getId(),
                ]
            )
        );
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

    private function getNode(): Node
    {
        /** @var NodeRepository $nodeRepository */
        $nodeRepository = $this->get('builder.repository.node');

        try {
            return $nodeRepository->find(
                $this->getRequest()->query->getInt('id')
            );
        } catch (NotFoundResourceException $e) {
            $this->redirect($this->getBackLink(['error' => 'non-existing']));
        }
    }

    private function getForm(UpdateNode $updateNode): Form
    {
        /** @var Form $form */
        $form = $this->get('builder.helper.field')->createForm(
            NodeType::class,
            $updateNode
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function updateNode(Form $form): UpdateNode
    {
        /** @var UpdateNode $updateNode */
        $updateNode = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($updateNode);

        return $updateNode;
    }
}
