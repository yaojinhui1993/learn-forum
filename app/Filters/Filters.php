<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        // We apply our fliters to the query builder
        $this->builder = $builder;

        $this->getFilters()
            ->filter(function ($filter) {
                return method_exists($this, $filter);
            })
            ->each(function ($filter, $value) {
                $this->$filter($value);
            });
        // foreach ($this->getFilters() as $filter => $value) {
        //     if (method_exists($this, $filter)) {
        //         $this->$filter($value);
        //     }
        // }

        return $this->builder;
    }

    public function getFilters()
    {
        return collect($this->request->intersect($this->filters))->flip();
    }
}
