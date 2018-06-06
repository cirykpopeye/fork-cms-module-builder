<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 0:49
 */

namespace Backend\Modules\Builder\Domain\SectionField;


class SectionFieldDataTransferObject
{
    private $sectionField;
    public $field;
    public $section;
    public $sequence;
    public $required;

    /**
     * SectionFieldDataTransferObject constructor.
     * @param $sectionField
     */
    public function __construct(SectionField $sectionField = null)
    {
        $this->sectionField = $sectionField;

        if (!$this->hasValidSectionField()) {
            $this->required = false;
            return;
        }

        $this->field = $sectionField->getField();
        $this->section = $sectionField->getSection();
        $this->sequence = $sectionField->getSequence();
        $this->required = $sectionField->isRequired();
    }

    public function hasValidSectionField() {
        return $this->sectionField instanceof SectionField;
    }


    /**
     * @return SectionField
     */
    public function getSectionField(): SectionField
    {
        return $this->sectionField;
    }

    /**
     * @param SectionField $sectionField
     */
    public function setSectionField(SectionField $sectionField): void
    {
        $this->sectionField = $sectionField;
    }
}