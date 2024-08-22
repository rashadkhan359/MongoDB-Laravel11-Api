<?php

namespace App\Filters;

use App\Service\FilterService;
// use Illuminate\Database\Eloquent\Builder;
use MongoDB\Laravel\Eloquent\Builder;

class UserFilter extends FilterService
{
    protected $allowedFilters = [
        'name' => ['eq', 'neq', 'like'],
        'phone' => ['eq', 'neq', 'like'],
        'email' => ['eq', 'neq', 'like'],
        'is_admin' => ['eq', 'neq'],
        'created_at' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'between'],
        'updated_at' => ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'between'],
    ];

    protected function applySearch(Builder $query, string $searchTerm): Builder
    {
        if (empty(trim($searchTerm))) {
            return $query;
        }

        $searchWords = preg_split('/\s+/', trim($searchTerm));

        return $query->where(function ($subQuery) use ($searchWords) {
            foreach ($searchWords as $word) {
                $subQuery->orWhere('name', 'like', "%$word%")
                    ->orWhere('email', 'like', "%$word%");
            }
        });
    }
}
