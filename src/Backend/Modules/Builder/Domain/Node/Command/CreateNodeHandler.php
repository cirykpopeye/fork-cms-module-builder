<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 15:47
 */

namespace Backend\Modules\Builder\Domain\Node\Command;


use Backend\Core\Engine\Model;
use Backend\Modules\Builder\Domain\Node\Node;
use Backend\Modules\Builder\Domain\Node\NodeRepository;
use Common\ModuleExtraType;

class CreateNodeHandler
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

    public function handle(CreateNode $createNode) {
        $createNode->extraId = $this->insertExtra();
        $node = Node::fromDataTransferObject($createNode);
        $this->nodeRepository->add($node);
        $createNode->setNode($node);
    }

    private function insertExtra() {
        return (int) Model::insertExtra(ModuleExtraType::widget(), 'Builder', 'Node');
    }
}