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
    private $options = [];

    public function __construct(
        $data,
        PaginationInterface $pagination,
        FormInterface $filterForm = null,
        $options = []
    )
    {
        $this->data = $data;
        $this->pagination = $pagination;
        $this->filterForm = $filterForm;
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