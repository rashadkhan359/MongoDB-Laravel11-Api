<?php

namespace App\Http\Requests\v1\Admin;

use App\Filters\BlogFilter;
use App\Http\Requests\v1\BaseFilterRequest;
use App\Service\FilterService;

class BlogIndexRequest extends BaseFilterRequest
{
    protected function getFilterService(): FilterService
    {
        return new BlogFilter();
    }
}
