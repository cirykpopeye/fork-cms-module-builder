<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:40
 */

namespace Backend\Modules\Builder\Domain\Field\Command;


use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Field\FieldRepository;
use Doctrine\Common\Util\Debug;

class UpdateFieldHandler
{
    /**
     * @var FieldRepository
     */
    private $fieldRepository;

    /**
     * CreateFieldHandler constructor.
     * @param FieldRepository $fieldRepository
     */
    public function __construct(FieldRepository $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    public function handle(UpdateField $updateField) {
        $field = Field::fromDataTransferObject($updateField);
        $this->fieldRepository->add($field);
        $updateField->setField($field);
    }
}