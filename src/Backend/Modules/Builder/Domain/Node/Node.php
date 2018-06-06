<?php

namespace Backend\Modules\Builder\Domain\Node;

use Backend\Core\Engine\Model;
use Backend\Modules\Builder\Domain\NodeContent\NodeContent;
use Backend\Modules\Builder\Domain\NodeContent\NodeContentDataTransferObject;
use Common\ModuleExtraType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Mapping as ORM;
use Backend\Modules\Builder\Domain\Section\Section;

/**
 * @ORM\Table(name="builder_node")
 * @ORM\Entity(repositoryClass="NodeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Node
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Node
     * @ORM\ManyToOne(targetEntity="Node", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $parent;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Node", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Backend\Modules\Builder\Domain\Section\Section")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\OneToMany(targetEntity="Backend\Modules\Builder\Domain\NodeContent\NodeContent", mappedBy="node", cascade={"persist"})
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $extraId;

    /**
     * @ORM\Column(type="string", name="nodeKey")
     */
    private $key;

    /**
     * Node constructor.
     * @param Section $section
     * @param string $title
     * @param int $extraId
     * @param string $key
     * @param Node|null $parent
     */
    public function __construct(Section $section, string $title, int $extraId, string $key, Node $parent = null)
    {
        $this->content = new ArrayCollection();
        $this->children = new ArrayCollection();

        $this->section = $section;
        $this->title = $title;
        $this->extraId = $extraId;
        $this->key = $key;
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @return Node
     */
    public function getParent(): ?Node
    {
        return $this->parent;
    }

    public function setSection(Section $section) {
        $this->section = $section;
    }

    public function addContent(NodeContent $content) {
        if (!$this->content->contains($content)) {
            $this->content->add($content);
        }
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->updateExtra();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate() {
        $this->updateExtra();
    }

    private function updateExtra() {
        $extras = Model::getExtras(array($this->extraId));

        if (empty($extras)) {
            //-- Create a new one
            $this->extraId = Model::insertExtra(ModuleExtraType::widget(), 'Builder', 'Node', 'Node');
        }

        $data = array(
            'extra_label' => (string) $this . ' (' . $this->section . ')',
            'id' => $this->id
        );

        Model::updateExtra($this->extraId, 'data', $data);
    }

    public function getPageContent() {
        //-- Return all fields, so it can read it's value
        $return = array();

        $return['key'] = $this->getKey();

        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $return['children'][] = $child->getPageContent();
        }


        /** @var NodeContent $content */
        foreach ($this->getContent() as $content) {
            $key = $content->getField()->getKey();

            if ($key === 'node') {
                $nodePageContent = Model::get('builder.repository.node')->find($content->getValue())->getPageContent();

                if (!isset($return['nodes'])) {
                    $return['nodes'] = array(
                        $nodePageContent
                    );
                } else {
                    $return['nodes'][] = $nodePageContent;
                }

                continue;
            }

            $return[$key] = $content->getValue();
        }

        return $return;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    private function transformContent($dataTransferContent) {
        $content = new ArrayCollection();

        /** @var NodeContentDataTransferObject $nodeContent */
        foreach ($dataTransferContent as $nodeContent) {
            $nodeContent->node = $this;
            if ($nodeContent->value) {
                $content->add(NodeContent::fromDataTransferObject($nodeContent));
            }
        }

        return $content;
    }

    public static function fromDataTransferObject(NodeDataTransferObject $dataTransferObject) {
        if ($dataTransferObject->hasValidNode()) {
            $node = $dataTransferObject->getNode();
            $node->content = $node->transformContent($dataTransferObject->content);
            $node->section = $dataTransferObject->section;
            $node->key = $dataTransferObject->key;
            $node->title = $dataTransferObject->title;
            $node->parent = $dataTransferObject->parent;
            return $node;
        }

        $node = new self(
            $dataTransferObject->section,
            $dataTransferObject->title,
            $dataTransferObject->extraId,
            $dataTransferObject->key,
            $dataTransferObject->parent
        );

        $node->content = $node->transformContent($dataTransferObject->content);

        return $node;
    }

    public function __toString()
    {
        return $this->title;
    }
}