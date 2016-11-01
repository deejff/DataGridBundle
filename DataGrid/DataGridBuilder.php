<?php

namespace Deejff\DataGridBundle\DataGrid;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

class DataGridBuilder
{
    const SORT_FIELD_WHITE_LIST_OPTION = 'sortFieldWhitelist';
    const SORT_LIMIT_OPTIONS = 'limitOptions';

    private $formFactory;
    private $paginator;
    private $limit = null;
    private $page = 1;
    /**
     * @var ResultDataConverterInterface
     */
    private $resultDataConverter;
    private $sortFieldWhiteList;
    private $defaultLimit;
    private $limitOptions = [
        10,
        50,
        100
    ];


    public function __construct(
        FormFactoryInterface $formFactory,
        PaginatorInterface $paginator,
        $defaultLimit = 10
    ) {
        $this->formFactory = $formFactory;
        $this->paginator = $paginator;
        $this->defaultLimit = $defaultLimit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    public function setResultDataConverter(ResultDataConverterInterface $resultDataConverter)
    {
        $this->resultDataConverter = $resultDataConverter;

        return $this;
    }

    public function setSortFieldWhiteList(array $whiteList = [])
    {
        $this->sortFieldWhiteList = $whiteList;

        return $this;
    }

    /**
     * @param Request                     $request
     * @param QueryBuilder                $queryBuilder
     * @param FormInterface               $filterForm
     * @param FilterQueryBuilderInterface $filterQueryBuilder
     * @param array                       $options
     *
     * @return DataGrid
     */
    public function build(
        Request $request,
        QueryBuilder $queryBuilder,
        FormInterface $filterForm = null,
        FilterQueryBuilderInterface $filterQueryBuilder = null,
        $options = []
    ) {
        if ($filterForm && $filterQueryBuilder) {
            $this->applyFilter($filterForm, $request, $queryBuilder, $filterQueryBuilder);
        }

        $query = $queryBuilder->getQuery();

        $options = $this->mergeOptions($request, $options);

        $pagination = $this->applyPagination($request, $query, $options);

        $result = $query->getResult();

        if ($this->resultDataConverter) {
            $result = $this->resultDataConverter->convert($result);
        }

        return new DataGrid($result, $pagination, $filterForm, $options);
    }

    private function applyFilter(
        FormInterface $form,
        Request $request,
        QueryBuilder $queryBuilder,
        FilterQueryBuilderInterface $filterQueryBuilder
    ) {
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
            $filterQueryBuilder->build($queryBuilder, $form->getData());
        } elseif($filterQueryBuilder instanceof ListIsNotFilteredQueryBuilderInterface) {
            $filterQueryBuilder->buildWhenIsNotFiltered($queryBuilder);
        }
    }

    private function mergeOptions($request, $options)
    {
        return array_merge(
            [
                'currentLimit' => $this->getCurrentLimit($request),
                'limitOptions' => $this->limitOptions,
            ],
            $options,
            [self::SORT_FIELD_WHITE_LIST_OPTION => $this->sortFieldWhiteList]
        );
    }

    private function applyPagination($request, Query $query, $options)
    {
        return $this->paginator->paginate(
            $query,
            $request->query->get('page', $this->page),
            $this->getCurrentLimit($request),
            $options
        );
    }

    private function getCurrentLimit($request)
    {
        $limit = $this->limit;

        if ($this->limit === null) {
            $limit = $this->defaultLimit;
        }

        return $request->query->get('limit', $limit);
    }
} 