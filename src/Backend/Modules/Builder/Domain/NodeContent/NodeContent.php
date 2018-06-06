<?php

namespace Backend\Modules\Builder\Domain\NodeContent;

use Backend\Core\Engine\Model;
use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Node\Node;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="builder_node_content")
 * @ORM\Entity()
 */
class NodeContent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Backend\Modules\Builder\Domain\Node\Node", inversedBy="content")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private $node;

    /**
     * @ORM\ManyToOne(targetEntity="Backend\Modules\Builder\Domain\Field\Field")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @ORM\Column(type="text")
     */
    private $value;

    /**
     * NodeContent constructor.
     * @param $node
     * @param $field
     * @param $value
     */
    public function __construct(Node $node, Field $field, $value)
    {
        $this->node = $node;
        $this->field = $field;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node): void
    {
        $this->node = $node;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    public static function fromDataTransferObject(NodeContentDataTransferObject $dataTransferObject) {
        if ($dataTransferObject->hasValidNodeContent()) {
            $nodeContent = $dataTransferObject->getNodeContent();
            $nodeContent->field = $dataTransferObject->field;

            if ($nodeContent->field->getType() === 'node') {
                $nodeContent->value = $dataTransferObject->value->getId();
            } else {
                $nodeContent->value = $dataTransferObject->value;
            }
            return $nodeContent;
        }

        $nodeContent = new self(
            $dataTransferObject->node,
            $dataTransferObject->field,
            $dataTransferObject->value
        );

        return $nodeContent;
    }
}