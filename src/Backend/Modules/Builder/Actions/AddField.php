<?php

namespace Backend\Modules\Builder\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Builder\Domain\Field\Command\CreateField;
use Backend\Modules\Builder\Domain\Field\FieldType;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Command\CreateContentBlock;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\ContentBlockType;
use Backend\Modules\ContentBlocks\Domain\ContentBlock\Event\ContentBlockCreated;
use Symfony\Component\Form\Form;

/**
 * This is the add-action, it will display a form to create a new item
 */
class AddField extends BackendBaseActionAdd
{
    public function execute(): void
    {
        parent::execute();

        $form = $this->getForm();

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());

            $this->parse();
            $this->display();

            return;
        }

        $createField = $this->createField($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'added',
                    'var' => $createField->title,
                ]
            )
        );
    }

    private function createField(Form $form): CreateField
    {
        $createField = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($createField);

        return $createField;
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

    private function getForm(): Form
    {
        $form = $this->createForm(
            FieldType::class,
            new CreateField()
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }
}
