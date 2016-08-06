<?php

namespace Deejff\DataGridBundle\DataGrid;

use Knp\Component\Pager\Pagination\PaginationInterface;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class DataGrid
{
    private $data;
    private $pagination;
    private $filterForm;
    private $filterFormView;
    private $filterAlwaysVisible = false;

    private $priorityFilters = [];
    private $options = [];

    public function __construct(
        $data,
        PaginationInterface $pagination,
        FormInterface $filterForm = null,
        $priorityFilters = [],
        $options = []
    )
    {
        $this->data = $data;
        $this->pagination = $pagination;
        $this->filterForm = $filterForm;
        $this->priorityFilters = $priorityFilters;
        $this->options = $options;

        if ($filterForm) {
           $this->filterFormView = $filterForm->createView();
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return FormInterface
     */
    public function getFilterForm()
    {
        return $this->filterForm;
    }

    /**
     * @return FormView
     */
    public function getFilterFormView()
    {
        return $this->filterFormView;
    }

    /**
     * @return PaginationInterface
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return bool
     */
    public function getFilterAlwaysVisible()
    {
        return $this->filterAlwaysVisible;
    }

    /**
     * @param $filterAlwaysVisible
     *
     * @return $this
     */
    public function setFilterAlwaysVisible($filterAlwaysVisible)
    {
        $this->filterAlwaysVisible = $filterAlwaysVisible;

        return $this;
    }

    /**
     * @return array
     */
    public function getPriorityFilters()
    {
        return $this->priorityFilters;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }
}