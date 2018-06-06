<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 1:35
 */

namespace Backend\Modules\Builder\Domain\Node;


use Backend\Modules\Builder\Domain\NodeContent\NodeContent;
use Backend\Modules\Builder\Domain\NodeContent\NodeContentDataTransferObject;

class NodeDataTransferObject
{
    private $node;
    public $section;
    public $content;
    public $title;
    public $extraId;
    public $key;
    public $parent;

    /**
     * NodeDataTransferObject constructor.
     * @param $node
     */
    public function __construct(Node $node = null)
    {
        $this->node = $node;

        if (!$this->hasValidNode()) {
            $this->extraId = 0;
            return;
        }

        $this->section = $node->getSection();
        $this->title = $node->getTitle();
        $this->key = $node->getKey();
        $this->parent = $node->getParent();

        /** @var NodeContent $content */
        foreach ($node->getContent() as $content) {
            $this->content[] = new NodeContentDataTransferObject($content);
        }
    }


    public function hasValidNode() {
        return $this->node instanceof Node;
    }

    /**
     * @return mixed
     */
    public function getNode(): Node
    {
        return $this->node;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node): void
    {
        $this->node = $node;
    }
}