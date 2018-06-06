<?php

namespace Backend\Modules\Builder\Domain\Section;

use Backend\Form\Type\EditorType;
use Backend\Modules\Builder\Domain\SectionField\SectionField;
use Backend\Modules\Builder\Domain\SectionField\SectionFieldDataTransferObject;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Util\Debug;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @ORM\Table(name="builder_section")
 * @ORM\Entity(repositoryClass="SectionRepository")
 */
class Section 
{
    const FIELD_TYPES = array(
        'text' => TextType::class,
        'textarea' => TextareaType::class,
        'editor' => EditorType::class,
        'node' => EntityType::class
    );

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Backend\Modules\Builder\Domain\SectionField\SectionField", mappedBy="section", cascade={"persist"})
     */
    private $sectionFields;

    /**
     * Section constructor.
     * @param $title
     */
    public function __construct($title)
    {
        $this->sectionFields = new ArrayCollection();

        $this->title = $title;
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSectionFields()
    {
        return $this->sectionFields;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public static function getFieldType($type) {
        if (isset(self::FIELD_TYPES[$type])) {
            return self::FIELD_TYPES[$type];
        }
        return self::FIELD_TYPES['text'];
    }

    public static function fromDataTransferObject(SectionDataTransferObject $dataTransferObject) {

        $sectionFields = new ArrayCollection();

        /** @var SectionFieldDataTransferObject $sectionFieldDataTransferObject */
        foreach ($dataTransferObject->getSectionFields() as $sectionFieldDataTransferObject) {
            $sectionFields->add(SectionField::fromDataTransferObject($sectionFieldDataTransferObject));
        }

        if ($dataTransferObject->hasValidSection()) {
            /** @var Section $section */
            $section = $dataTransferObject->getSection();
            $section->title = $dataTransferObject->title;
            $section->sectionFields = $sectionFields;
            return $section;
        }

        $section = new self(
            $dataTransferObject->title
        );

        $section->sectionFields = $sectionFields;

        return $section;
    }

    public function __toString()
    {
        return $this->title;
    }
}