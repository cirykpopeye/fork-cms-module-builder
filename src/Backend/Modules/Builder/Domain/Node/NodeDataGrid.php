<?php

namespace Backend\Modules\Builder\Domain\Node;

use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGrid;
use Backend\Core\Engine\DataGridDatabase;
use Backend\Core\Engine\Model;
use Backend\Core\Language\Language;

/**
 * @TODO replace with a doctrine implementation of the data grid
 */
class NodeDataGrid extends DataGrid
{
    public function __construct($array)
    {
//        parent::__construct(
//            'SELECT i.id, i.title, i.nodeKey as `key`, s.title as section
//                FROM builder_node AS i
//                INNER JOIN builder_section as s ON s.id = i.section_id'
//        );

        $source = new NodeDataGridSource($array);
        parent::__construct($source);


        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('EditNode')) {
            $editUrl = Model::createUrlForAction('EditNode', null, null, ['id' => '[id]'], false);
            $this->setColumnURL('title', $editUrl);
            $this->addColumn('edit', null, Language::lbl('Edit'), $editUrl, Language::lbl('Edit'));
        }
    }

    public static function getHtml($array): string
    {
        return (new self($array))->getContent();
    }
}
