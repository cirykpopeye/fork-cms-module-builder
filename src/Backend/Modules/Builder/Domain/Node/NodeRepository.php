<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 22:38
 */

namespace Backend\Modules\Builder\Domain\Node;


use Doctrine\ORM\EntityRepository;

class NodeRepository extends EntityRepository
{
    public function add(Node $node) {
        $this->getEntityManager()->persist($node);
    }
}