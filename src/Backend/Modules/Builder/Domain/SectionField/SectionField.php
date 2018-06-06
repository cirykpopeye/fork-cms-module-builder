<?php

namespace Backend\Modules\Builder\Domain\SectionField;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="builder_section_field")
 * @ORM\Entity()
 */
class SectionField
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Backend\Modules\Builder\Domain\Section\Section", inversedBy="sectionFields")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @ORM\ManyToOne(targetEntity="Backend\Modules\Builder\Domain\Field\Field")
     * @ORM\JoinColumn(name="field_id", referencedColumnName="id")
     */
    private $field;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $required;

    /**
     * @ORM\Column(type="integer")
     */
    private $sequence;

    /**
     * SectionField constructor.
     * @param $section
     * @param $field
     * @param $sequence
     * @param $required
     */
    public function __construct($section, $field, $sequence, $required)
    {
        $this->section = $section;
        $this->field = $field;
        $this->sequence = $sequence;
        $this->required = $required;
    }

    public function getField() {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @return mixed
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section): void
    {
        $this->section = $section;
    }

    /**
     * @param mixed $field
     */
    public function setField($field): void
    {
        $this->field = $field;
    }

    /**
     * @param mixed $sequence
     */
    public function setSequence($sequence): void
    {
        $this->sequence = $sequence;
    }

    public static function fromDataTransferObject(SectionFieldDataTransferObject $dataTransferObject) {
        if ($dataTransferObject->hasValidSectionField()) {
            $sectionField = $dataTransferObject->getSectionField();
            $sectionField->field = $dataTransferObject->field;
            $sectionField->sequence = $dataTransferObject->sequence;
            $sectionField->required = $dataTransferObject->required;
            return $sectionField;
        }

        $sectionField = new self(
            $dataTransferObject->section,
            $dataTransferObject->field,
            $dataTransferObject->sequence,
            $dataTransferObject->required
        );

        return $sectionField;
    }
}