<?php

function h(string $tag, array | null $props = null, string | array ...$children)
{
    $mappedProps = "";
    $result = "";
    if (!is_null($props)) {
        foreach ($props as $key => $value) {
            $mappedProps .= " $key=\"$value\" ";
        }
    }

    $result .= "<$tag $mappedProps>";
    foreach ($children as  $child) {
        if (gettype($child) == "array") {
            foreach ($child as $subChild) {
                $result .= $subChild;
            }
        } else $result .= $child;
    }
    $result .= "</$tag>";
    return $result;
}
