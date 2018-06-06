<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 22:23
 */

namespace Backend\Modules\Builder\Domain\Field;


use Doctrine\ORM\EntityRepository;

class FieldRepository extends EntityRepository
{
    public function add(Field $field) {
        $this->getEntityManager()->persist($field);
    }
}