<?php

namespace Backend\Modules\Builder\Domain\Section;

use Doctrine\ORM\EntityRepository;

class SectionRepository extends EntityRepository
{
    public function add(Section $section) {
        $this->getEntityManager()->persist($section);
    }
}