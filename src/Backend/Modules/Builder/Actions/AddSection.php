<?php

namespace Backend\Modules\Builder\Actions;

use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Builder\Domain\Section\SectionType;
use Backend\Modules\Builder\Domain\Section\Command\CreateSection;
use Symfony\Component\Form\Form;

/**
 * This is the add-action, it will display a form to create a new item
 */
class AddSection extends BackendBaseActionAdd
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

        $createSection = $this->createSection($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'added',
                    'var' => $createSection->title,
                ]
            )
        );
    }

    private function createSection(Form $form): CreateSection
    {
        $createSection = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($createSection);

        return $createSection;
    }

    private function getBackLink(array $parameters = []): string
    {
        return BackendModel::createUrlForAction(
            'Sections',
            null,
            null,
            $parameters
        );
    }

    private function getForm(): Form
    {
        $form = $this->createForm(
            SectionType::class,
            new CreateSection()
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }
}
