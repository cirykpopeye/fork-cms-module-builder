<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 14:17
 */

namespace Backend\Modules\Builder\Helper;


use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;

class FieldHelper
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * FieldHelper constructor.
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function createForm($type, $data): Form {
        return $this->formFactory->create($type, $data);
    }
}