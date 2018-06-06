<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:40
 */

namespace Backend\Modules\Builder\Domain\Section\Command;


use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Field\FieldRepository;
use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\Builder\Domain\Section\SectionRepository;
use Doctrine\Common\Util\Debug;

class CreateSectionHandler
{
    /**
     * @var SectionRepository
     */
    private $sectionRepository;

    /**
     * CreateSectionHandler constructor.
     * @param SectionRepository $sectionRepository
     */
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function handle(CreateSection $createSection) {
        $section = Section::fromDataTransferObject($createSection);
        $this->sectionRepository->add($section);
        $createSection->setSection($section);
    }
}