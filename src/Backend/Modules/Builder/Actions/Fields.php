<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 10/05/2018
 * Time: 23:58
 */

namespace Backend\Modules\Builder\Actions;


use Backend\Core\Engine\Base\ActionIndex;
use Backend\Modules\Builder\Domain\Field\FieldDataGrid;

class Fields extends ActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->template->assign('dataGrid', FieldDataGrid::getHtml());
        $this->parse();
        $this->display();
    }
}