<?php

class service_common
{
    public static function prepare_array_items($array_items): array
    {
        $array_items = explode(',', $array_items);
        $array_items = array_filter(array_map('trim', $array_items), function($item) {
            return !empty($item);
        });

        $array_items = array_values($array_items);
        return $array_items;
    }
}