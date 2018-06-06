<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 0:05
 */

namespace Backend\Modules\Builder\Domain\Field\Command;


use Backend\Modules\Builder\Domain\Field\Field;
use Backend\Modules\Builder\Domain\Field\FieldDataTransferObject;

class UpdateField extends FieldDataTransferObject
{

    /**
     * UpdateField constructor.
     * @param Field $field
     */
    public function __construct(Field $field)
    {
        parent::__construct($field);
    }
}