<?php
/**
 * Created by PhpStorm.
 * User: Ciryk
 * Date: 11/05/2018
 * Time: 17:45
 */

namespace Backend\Modules\Builder\Domain\Node;


class NodeDataGridSource extends \SpoonDatagridSourceArray
{
    private $categories;
    private $level;


    public function __construct(array $array)
    {
        parent::__construct($array);

        $this->transformChildren();
    }

    private function transformChildren() {
        foreach ($this->data as $category) {
            $this->level = 0;
            $tmpCategory = $category;
            unset($tmpCategory['children']);
            $this->categories[] = $tmpCategory;
            $this->moveChildren($category);
        }
        $this->data = $this->categories;
    }

    private function moveChildren($category) {
        if (isset($category['children'])) {
            $this->level++;
            foreach ($category['children'] as $child) {
                $this->moveChildren($child);
                //-- Add indentation
                $child['title'] = str_repeat('--- ', $this->level) . $child['title'];
                $this->categories[] = $child;
            }
        }
    }

}