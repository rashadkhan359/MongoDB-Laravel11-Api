<?php

namespace App\Http\Requests\v1\Admin;

use App\Filters\UserFilter;
use App\Http\Requests\v1\BaseFilterRequest;
use App\Service\FilterService;

class UserIndexRequest extends BaseFilterRequest
{
    protected function getFilterService(): FilterService
    {
        return new UserFilter();
    }
}
