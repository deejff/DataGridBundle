<?php

namespace Deejff\DataGridBundle\DataGrid;

use Doctrine\ORM\QueryBuilder;

interface FilterQueryBuilderInterface
{
    public function build(QueryBuilder $qb, $data);
} 