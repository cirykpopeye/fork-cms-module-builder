<?php

namespace Backend\Modules\Builder\Domain\Field;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="builder_field")
 * @ORM\Entity(repositoryClass="FieldRepository")
 */
class Field
{
     /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="fieldKey")
     */
    private $key;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * Field constructor.
     * @param $key
     * @param $title
     * @param $type
     */
    public function __construct($key, $title, $type)
    {
        $this->key = $key;
        $this->title = $title;
        $this->type = $type;
    }

    public function getId() {
        return $this->id;
    }

    public function getKey() {
        return $this->key;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getType() {
        return $this->type;
    }

    public static function fromDataTransferObject(FieldDataTransferObject $dataTransferObject) {
        if ($dataTransferObject->hasValidField()) {
            $field = $dataTransferObject->getField();
            $field->key = $dataTransferObject->key;
            $field->title = $dataTransferObject->title;
            $field->type = $dataTransferObject->type;
            return $field;
        }

        $field = new self(
            $dataTransferObject->key,
            $dataTransferObject->title,
            $dataTransferObject->type
        );

        return $field;
    }

    public function __toString()
    {
        return $this->title;
    }
}