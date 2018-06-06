<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 0:14
 */

namespace Backend\Modules\Builder\Domain\Section;


use Backend\Modules\Builder\Domain\SectionField\SectionField;
use Backend\Modules\Builder\Domain\SectionField\SectionFieldDataTransferObject;
use Doctrine\Common\Collections\ArrayCollection;

class SectionDataTransferObject
{
    private $section;
    public $title;
    private $sectionFields;
    private $seq = 1;

    /**
     * SectionDataTransferObject constructor.
     * @param $section
     */
    public function __construct(Section $section = null)
    {
        $this->section = $section;

        if (!$this->hasValidSection()) {
            $this->sectionFields = new ArrayCollection();
            return;
        }

        $this->title = $section->getTitle();
        $this->sectionFields = new ArrayCollection();

        foreach ($section->getSectionFields() as $sectionField) {
            $this->sectionFields->add(new SectionFieldDataTransferObject($sectionField)) ;
        }
    }

    /**
     * @return mixed
     */
    public function getSectionFields()
    {
        return $this->sectionFields;
    }


    public function hasValidSection() {
        return $this->section instanceof Section;
    }

    public function addSectionField(SectionFieldDataTransferObject $dataTransferObject) {
        $dataTransferObject->section = $this->section;
        $dataTransferObject->sequence = $this->seq;
        $this->seq++;
        $this->sectionFields->add($dataTransferObject);
    }

    public function removeSectionField(SectionFieldDataTransferObject $dataTransferObject) {
        $this->sectionFields->removeElement($dataTransferObject);
        $sectionField = SectionField::fromDataTransferObject($dataTransferObject);
        $this->section->getSectionFields()->removeElement($sectionField);
        $sectionField->setSection(null);
    }

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section): void
    {
        $this->section = $section;
    }


}