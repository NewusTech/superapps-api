<?php
namespace App\Helpers;
use Illuminate\Database\Eloquent\Builder;

class FilterHelper
{
    public static function applyFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $query->where($field, 'like', "%$value%");
            }
        }
        return $query;
    }

    public static function applySearch(Builder $query, $searchTerm, array $searchFields): Builder
    {
        $query->where(function($query) use ($searchTerm, $searchFields) {
            foreach ($searchFields as $field) {
                if (strpos($field, '.') !== false) {
                    [$relation, $column] = explode('.', $field);
                    $query->orWhereHas($relation, function($query) use ($searchTerm, $column) {
                        $query->where($column, 'like', "%$searchTerm%");
                    });
                } else {
                    $query->orWhere($field, 'like', "%$searchTerm%");
                }
            }
        });

        return $query;
    }

    public static function applyLimit(Builder $query, $limit): Builder
    {
        return $query->limit($limit);}
}
