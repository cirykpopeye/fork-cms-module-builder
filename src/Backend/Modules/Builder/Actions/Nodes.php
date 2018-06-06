<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:58
 */

namespace Backend\Modules\Builder\Actions;


use Backend\Core\Engine\Base\ActionIndex;
use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeDataGrid;

class Nodes extends ActionIndex
{
    private $records;
    public function execute(): void
    {
        parent::execute();

        $this->records = array();
        $nodes = $this->get('builder.repository.node')->findBy(array('parent' => null), array('title' => 'ASC'));

        /** @var Node $node */
        foreach ($nodes as $node) {
            $this->records[$node->getId()] = $this->getNode($node);
        }

        $this->template->assign('dataGrid', NodeDataGrid::getHtml($this->records));
        $this->parse();
        $this->display();
    }

    private function getNode(Node &$node) {
        $categoryRecord = array();
        $categoryRecord['id'] = $node->getId();
        $categoryRecord['title'] = $node->getTitle();
        $categoryRecord['section'] = $node->getSection();
        $children = $node->getChildren();

        foreach ($children as $child) {
            $categoryRecord['children'][] = $this->getNode($child);
        }

        return $categoryRecord;
    }

}