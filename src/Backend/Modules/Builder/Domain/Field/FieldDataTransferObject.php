<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:31
 */

namespace Backend\Modules\Builder\Domain\Field;


class FieldDataTransferObject
{
    private $field;
    public $key;
    public $title;
    public $type;

    /**
     * FieldDataTransferObject constructor.
     * @param $field
     */
    public function __construct(Field $field = null)
    {
        $this->field = $field;

        if (!$this->hasValidField()) {
            return;
        }

        $this->key = $field->getKey();
        $this->title = $field->getTitle();
        $this->type = $field->getType();
    }

    public function hasValidField() {
        return $this->field instanceof Field;
    }

    /**
     * @return Field
     */
    public function getField(): Field
    {
        return $this->field;
    }

    /**
     * @param Field $field
     */
    public function setField(Field $field): void
    {
        $this->field = $field;
    }
}