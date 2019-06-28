<?php
if (!function_exists('array_insert')) {
    /**
     * @param array $array
     * @param int $position position of to insert array
     * @param to insert array
     */
    function array_insert($array, $position, $insert_array)
    {
        $first_array = array_splice($array, 0, $position);
        return array_merge($first_array, $insert_array, $array);
    }
}