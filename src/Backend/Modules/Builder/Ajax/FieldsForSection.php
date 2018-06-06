<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 12:53
 */

namespace Backend\Modules\Builder\Ajax;


use Backend\Core\Engine\Base\AjaxAction;
use Backend\Core\Engine\TwigTemplate;
use Backend\Modules\Builder\Domain\Node\Command\CreateNode;
use Backend\Modules\Builder\Domain\Node\Command\UpdateNode;
use Backend\Modules\Builder\Domain\Node\NodeType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;

class FieldsForSection extends AjaxAction
{
    public function execute(): void
    {
        parent::execute();

        $sectionId = $this->getRequest()->request->get('section_id');
        $nodeId = $this->getRequest()->request->get('node_id');

        $section = $this->get('builder.repository.section')->find($sectionId);

        $nodeCommand = new CreateNode();
        $nodeCommand->section = $section;

        if ($nodeId) {
            $node = $this->get('builder.repository.node')->find($nodeId);
            $nodeCommand = new UpdateNode($node);
            $nodeCommand->section = $section;
        }

        /** @var Form $form */
        $form = $this->get('builder.helper.field')->createForm(
            NodeType::class,
            $nodeCommand
        );

        $form->handleRequest($this->getRequest());

        /** @var TwigTemplate $template */
        $template = $this->get('templating');
        $template->assign('form', $form->createView());

        $this->output(Response::HTTP_OK, array('template' => $template->getContent(BACKEND_MODULES_PATH . '/Builder/Resources/views/node/fields.html.twig')));
    }
}