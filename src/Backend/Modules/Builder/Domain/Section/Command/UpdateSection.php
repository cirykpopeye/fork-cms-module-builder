<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 1:05
 */

namespace Backend\Modules\Builder\Domain\Section\Command;


use Backend\Modules\Builder\Domain\Section\Section;
use Backend\Modules\Builder\Domain\Section\SectionDataTransferObject;

class UpdateSection extends SectionDataTransferObject
{

    /**
     * UpdateSection constructor.
     */
    public function __construct(Section $section)
    {
        parent::__construct($section);
    }
}