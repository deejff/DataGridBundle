<?php

namespace Deejff\DataGridBundle\DataGrid;

use Doctrine\ORM\QueryBuilder;

interface ListIsNotFilteredQueryBuilderInterface
{
    public function buildWhenIsNotFiltered(QueryBuilder $qb);
}