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

class CreateFieldHandler
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

    public function handle(CreateField $createField) {
        $field = Field::fromDataTransferObject($createField);
        $this->fieldRepository->add($field);
        $createField->setField($field);
    }
}