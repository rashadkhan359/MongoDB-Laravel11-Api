<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\Service\FilterService;

abstract class BaseFilterRequest extends FormRequest
{
    abstract protected function getFilterService(): FilterService;

    public function rules()
    {
        $filterService = $this->getFilterService();
        $allowedFields = array_keys($filterService->getAllowedFilters());
        $allowedOperators = $this->getAllowedOperators($filterService->getAllowedFilters());
        
        return [
            'search' => 'nullable|string',
            'filters' => 'nullable|array',
            'filters.*.field' => 'required|string|in:' . implode(',', $allowedFields),
            'filters.*.operator' => 'required|string|in:' . implode(',', $allowedOperators),
            'filters.*.value' => 'required',
            'sort' => 'sometimes|array',
            'sort.*' => 'string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1'
        ];
    }

    private function getAllowedOperators(array $allowedFilters): array
    {
        return array_unique(array_merge(...array_values($allowedFilters)));
    }
}
