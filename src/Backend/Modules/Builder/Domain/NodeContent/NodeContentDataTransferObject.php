<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 1:40
 */

namespace Backend\Modules\Builder\Domain\NodeContent;


use Backend\Core\Engine\Model;
use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Node\Node;

class NodeContentDataTransferObject
{
    private $nodeContent;

    /**
     * @var Node
     */
    public $node;

    /**
     * @var Field
     */
    public $field;
    public $value;

    /**
     * NodeContentDataTransferObject constructor.
     * @param $nodeContent
     */
    public function __construct(NodeContent $nodeContent = null)
    {
        $this->nodeContent = $nodeContent;

        if (!$this->hasValidNodeContent()) {
            return;
        }

        $this->node = $nodeContent->getNode();
        $this->field = $nodeContent->getField();

        if ($this->field->getType() === 'node') {
            $this->value = Model::get('builder.repository.node')->find($nodeContent->getValue());
            return;
        }
        $this->value = $nodeContent->getValue();
    }

    public function hasValidNodeContent() {
        return $this->nodeContent instanceof NodeContent;
    }

    /**
     * @return NodeContent
     */
    public function getNodeContent(): NodeContent
    {
        return $this->nodeContent;
    }

    /**
     * @param NodeContent $nodeContent
     */
    public function setNodeContent(NodeContent $nodeContent): void
    {
        $this->nodeContent = $nodeContent;
    }
}