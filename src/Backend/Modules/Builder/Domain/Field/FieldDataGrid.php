<?php

namespace Backend\Modules\Builder\Domain\Field;

use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;

/**
 * @TODO replace with a doctrine implementation of the data grid
 */
class FieldDataGrid extends DataGridDatabase
{
    public function __construct()
    {
        parent::__construct(
            'SELECT i.id, i.title, i.fieldKey as `key`, i.type
             FROM builder_field AS i'
        );

        $this->setSortingColumns(['title'], 'title');

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('EditField')) {
            $editUrl = Model::createUrlForAction('EditField', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml(): string
    {
        return (new self())->getContent();
    }
}
