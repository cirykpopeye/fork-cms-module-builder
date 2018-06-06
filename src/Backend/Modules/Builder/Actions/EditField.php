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
class EditField extends BackendBaseActionEdit
{
    public function execute(): void
    {
        parent::execute();

        $field = $this->getField();

        $form = $this->getForm($field);

        $deleteForm = $this->createForm(
            DeleteType::class,
            ['id' => $field->getId()],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());
            $this->template->assign('field', $field);

            $this->parse();
            $this->display();

            return;
        }

        /** @var UpdateField $updateField */
        $updateField = $this->updateField($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'edited',
                    'var' => $updateField->title,
                    'highlight' => 'row-' . $field->getId(),
                ]
            )
        );
    }

    private function getBackLink(array $parameters = []): string
    {
        return BackendModel::createUrlForAction(
            'Fields',
            null,
            null,
            $parameters
        );
    }

    private function getField(): Field
    {
        /** @var FieldRepository $fieldRepository */
        $fieldRepository = $this->get('builder.repository.field');

        try {
            return $fieldRepository->find(
                $this->getRequest()->query->getInt('id')
            );
        } catch (NotFoundResourceException $e) {
            $this->redirect($this->getBackLink(['error' => 'non-existing']));
        }
    }

    private function getForm(Field $field): Form
    {
        $form = $this->createForm(
            FieldType::class,
            new UpdateField($field)
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function updateField(Form $form): UpdateField
    {
        /** @var UpdateField $updateField */
        $updateField = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($updateField);

        return $updateField;
    }
}
