<?php

namespace Backend\Modules\Builder\Installer;

use Backend\Core\Engine\Model;
use Backend\Core\Installer\ModuleInstaller;
use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\NodeContent\NodeContent;
use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\Builder\Domain\SectionField\SectionField;

/**
 * Installer for the builder
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('Builder');
        $this->configureEntities();
        $this->configureBackendNavigation();
        $this->configureBackendRights();
    }

    private function configureBackendNavigation(): void
    {
        // Set navigation for "Modules"
        $navigationModulesId = $this->setNavigation(null, 'Modules');

        $navigationBuilderId = $this->setNavigation($navigationModulesId, 'Builder');

        $this->setNavigation(
            $navigationBuilderId,
            'Fields',
            'builder/fields',
            ['builder/add_field', 'builder/edit_field']
        );

        $this->setNavigation(
            $navigationBuilderId,
            'Nodes',
            'builder/nodes',
            ['builder/add_node', 'builder/edit_node']
        );

        $this->setNavigation(
            $navigationBuilderId,
            'Sections',
            'builder/sections',
            ['builder/add_section', 'builder/edit_section']
        );
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, $this->getModule());
        $this->setActionRights(1, $this->getModule(), 'AddField');
        $this->setActionRights(1, $this->getModule(), 'AddNode');
        $this->setActionRights(1, $this->getModule(), 'AddSection');
        $this->setActionRights(1, $this->getModule(), 'EditField');
        $this->setActionRights(1, $this->getModule(), 'EditNode');
        $this->setActionRights(1, $this->getModule(), 'EditSection');
        $this->setActionRights(1, $this->getModule(), 'Sections');
        $this->setActionRights(1, $this->getModule(), 'Nodes');
        $this->setActionRights(1, $this->getModule(), 'Fields');
    }

    private function configureEntities(): void
    {
        Model::get('fork.entity.create_schema')->forEntityClass(Field::class);
        Model::get('fork.entity.create_schema')->forEntityClass(Section::class);
        Model::get('fork.entity.create_schema')->forEntityClass(SectionField::class);
        Model::get('fork.entity.create_schema')->forEntityClass(Node::class);
        Model::get('fork.entity.create_schema')->forEntityClass(NodeContent::class);
    }
}
