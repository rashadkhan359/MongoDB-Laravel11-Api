<?php

namespace App\Service;

// use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;
use MongoDB\Laravel\Eloquent\Builder;

abstract class FilterService
{
    protected $allowedFilters = [];
    protected $searchableFields = [];

    public function apply(Builder $query, array $filterParams): Builder
    {

        if (isset($filterParams['search'])) {
            $query = $this->applySearch($query, $filterParams['search']);
        }

        if (isset($filterParams['filters'])) {
            $query = $this->applyFilters($query, $filterParams['filters']);
        }

        if (isset($filterParams['sort'])) {
            $query = $this->applySort($query, $filterParams['sort']);
        }

        return $query;
    }

    public function getAllowedFilters(): array
    {
        return $this->allowedFilters;
    }

    public function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    protected function applySearch(Builder $query, string $searchTerm): Builder
    {
        return $query->where(function ($q) use ($searchTerm) {
            foreach ($this->searchableFields as $field) {
                $q->orWhere($field, 'LIKE', "%{$searchTerm}%");
            }
        });
    }

    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter) {
            if ($this->isValidFilter($filter)) {
                $method = $filter['operator'];
                $query = $this->$method($query, $filter['field'], $filter['value']);
            }
        }
        return $query;
    }

    protected function isValidFilter(array $filter): bool
    {
        return isset($this->allowedFilters[$filter['field']]) &&
            in_array($filter['operator'], $this->allowedFilters[$filter['field']]);
    }

    protected function applySort(Builder $query, array $sort): Builder
    {
        foreach ($sort as $field => $direction) {
            if (isset($this->allowedFilters[$field])) {
                $query->orderBy($field, $direction);
            }
        }
        return $query;
    }

    protected function eq(Builder $query, string $field, $value): Builder
    {
        if (MongoDateTimeService::isDateField($field)) {
            return $this->dateEquals($query, $field, $value);
        }
        return $query->where($field, '=', MongoDateTimeService::toMongoDateTime($value));
    }

    protected function dateEquals(Builder $query, string $field, $value): Builder
    {
        $startOfDay = new \MongoDB\BSON\UTCDateTime(Carbon::createFromFormat("Y-m-d", $value)->startOfDay());
        $endOfDay = new \MongoDB\BSON\UTCDateTime(Carbon::createFromFormat("Y-m-d", $value)->endOfDay());

        return $query->where($field, '>=', $startOfDay)
            ->where($field, '<', $endOfDay);
    }


    protected function neq(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, '!=', MongoDateTimeService::toMongoDateTime($value));
    }

    protected function gt(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, '>', MongoDateTimeService::toMongoDateTime($value));
    }

    protected function gte(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, '>=', MongoDateTimeService::toMongoDateTime($value));
    }

    protected function lt(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, '<', MongoDateTimeService::toMongoDateTime($value, 'lt'));
    }

    protected function lte(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, '<=', MongoDateTimeService::toMongoDateTime($value, 'lte'));
    }

    protected function like(Builder $query, string $field, $value): Builder
    {
        return $query->where($field, 'LIKE', "%{$value}%");
    }

    protected function in(Builder $query, string $field, array $values): Builder
    {
        return $query->whereIn($field, $values);
    }

    protected function between(Builder $query, string $field, array $values): Builder
    {
        $formattedValues = [];
        foreach ($values as $key => $value) {
            $formattedValues[$key] = MongoDateTimeService::toMongoDateTime($value);
        }
        return $query->whereBetween($field, $formattedValues);
    }

    protected function or(Builder $query, array $conditions): Builder
    {
        return $query->where(function ($q) use ($conditions) {
            foreach ($conditions as $condition) {
                if ($this->isValidFilter($condition)) {
                    $method = $condition['operator'];
                    $q->orWhere(function ($subQ) use ($method, $condition) {
                        $this->$method($subQ, $condition['field'], $condition['value']);
                    });
                }
            }
        });
    }
}
