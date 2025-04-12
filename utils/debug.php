<?php

/**
 * Dump and die
 *
 * @param mixed $data The data to dump.
 */
function dd($data)
{
    echo '<pre>';
    die(var_dump($data));
    echo '</pre>';
}


/**
 * Only dump
 *
 * @param mixed $data The data to output.
 */

function od($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}
