<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 22:34
 */

namespace Frontend\Modules\Builder\Widgets;


use Doctrine\Common\Util\Debug;
use Frontend\Core\Engine\Base\Widget;

class Node extends Widget
{
    private $node;
    public function execute(): void
    {
        parent::execute();

        $nodeId = $this->data['id'];
        $this->node = $this->get('builder.repository.node')->find($nodeId);

        $this->template->assign('context', $this->node->getPageContent());

        $this->loadTemplate();
    }

    protected function loadTemplate(string $path = null): void
    {
        //-- Check if template exists for specific node
        $templatePath = FRONTEND_MODULES_PATH . '/Builder/Resources/templates/node/' . $this->node->getKey() . '.html.twig';
        if (file_exists($templatePath)) {
            parent::loadTemplate($templatePath);
            return;
        }
        parent::loadTemplate($path);
    }
}