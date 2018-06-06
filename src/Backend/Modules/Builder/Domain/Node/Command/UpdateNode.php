<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 16:05
 */

namespace Backend\Modules\Builder\Domain\Node\Command;


use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeDataTransferObject;

class UpdateNode extends NodeDataTransferObject
{
    /**
     * UpdateNode constructor.
     * @param Node $node
     */
    public function __construct(Node $node)
    {
        parent::__construct($node);
    }
}