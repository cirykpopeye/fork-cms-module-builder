<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 15:47
 */

namespace Backend\Modules\Builder\Domain\Node\Command;


use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeRepository;

class UpdateNodeHandler
{
    /**
     * @var NodeRepository
     */
    private $nodeRepository;

    /**
     * CreateNodeHandler constructor.
     * @param NodeRepository $nodeRepository
     */
    public function __construct(NodeRepository $nodeRepository)
    {
        $this->nodeRepository = $nodeRepository;
    }

    public function handle(UpdateNode $updateNode) {
        $node = Node::fromDataTransferObject($updateNode);
        $this->nodeRepository->add($node);
        $updateNode->setNode($node);
    }
}