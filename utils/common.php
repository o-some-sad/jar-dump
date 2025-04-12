<?php

/**
 * pick specific keys from an array
 */
function pick(array $array, array $keys)
{
    if(array_is_list($array)){
        throw new Exception("\$array argument must be an associative array");
    }
    if(!array_is_list($keys)){
        throw new Exception("\$keys argument must be an array");
    }
    $result = [];
    foreach ($keys as $key) {
        if (isset($array[$key])) {
            $result[$key] = $array[$key];
        }
    }
    return $result;
}

/**
 * @deprecated
 */
function map(array $data, Closure $fn)
{
    foreach ($data as $row) {
        $fn($row);
    }
}
