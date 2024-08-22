<?php

namespace App\Service;

use Carbon\Carbon;

class MongoDateTimeService
{
    public static function toMongoDateTime($value, $operator = null, $field = null, $format = "Y-m-d")
    {
        // If the value is already a MongoDB UTCDateTime, return it as is.
        if ($value instanceof \MongoDB\BSON\UTCDateTime) {
            return $value;
        }

        // If the field is a datetime or the value looks like a datetime string, convert it.
        if (self::isDateField($field) || self::isDateString($value)) {
            $carbonDate = Carbon::createFromFormat($format, $value);
            // If the operator is lte or lt, set the time to the end of the day.
            if ($operator === 'lte') {
                $carbonDate->endOfDay();
            }

            return new \MongoDB\BSON\UTCDateTime($carbonDate);
        }

        // Otherwise, return the value as is.
        return $value;
    }

    public static function isDateField($field)
    {
        // Check if the field is a known datetime field (you might need to customize this).
        return in_array($field, ['created_at', 'updated_at', 'deleted_at']);
    }

    public static function isDateString($value)
    {
        // Basic check to see if the value looks like a date (this can be improved).
        return preg_match('/\d{4}-\d{2}-\d{2}/', $value) || $value instanceof Carbon;
    }

}
