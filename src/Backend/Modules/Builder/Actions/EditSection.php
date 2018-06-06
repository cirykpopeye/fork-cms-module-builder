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
use Backend\Modules\Builder\Domain\Section\Command\UpdateSection;
use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\Builder\Domain\Section\SectionRepository;
use Backend\Modules\Builder\Domain\Section\SectionType;
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
class EditSection extends BackendBaseActionEdit
{
    public function execute(): void
    {
        parent::execute();

        $section = $this->getSection();

        $form = $this->getForm($section);

        $deleteForm = $this->createForm(
            DeleteType::class,
            ['id' => $section->getId()],
            ['module' => $this->getModule()]
        );
        $this->template->assign('deleteForm', $deleteForm->createView());

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->template->assign('form', $form->createView());
            $this->template->assign('section', $section);

            $this->parse();
            $this->display();

            return;
        }

        /** @var UpdateSection $updateSection */
        $updateSection = $this->updateSection($form);

        $this->redirect(
            $this->getBackLink(
                [
                    'report' => 'edited',
                    'var' => $updateSection->title,
                    'highlight' => 'row-' . $section->getId(),
                ]
            )
        );
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

    private function getSection(): Section
    {
        /** @var SectionRepository $sectionRepository */
        $sectionRepository = $this->get('builder.repository.section');

        try {
            return $sectionRepository->find(
                $this->getRequest()->query->getInt('id')
            );
        } catch (NotFoundResourceException $e) {
            $this->redirect($this->getBackLink(['error' => 'non-existing']));
        }
    }

    private function getForm(Section $section): Form
    {
        $form = $this->createForm(
            SectionType::class,
            new UpdateSection($section)
        );

        $form->handleRequest($this->getRequest());

        return $form;
    }

    private function updateSection(Form $form): UpdateSection
    {
        /** @var UpdateSection $updateSection */
        $updateSection = $form->getData();

        // The command bus will handle the saving of the content block in the database.
        $this->get('command_bus')->handle($updateSection);

        return $updateSection;
    }
}
