<?php

namespace App\Filters;

use App\Service\FilterService;
use MongoDB\Laravel\Eloquent\Builder;

class BlogFilter extends FilterService
{
    protected $allowedFilters = [
        'title' => ['like'],
        'content' => ['like'],
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
                $subQuery->orWhere('title', 'like', "%$word%");
            }
        });
    }
}
