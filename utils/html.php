<?php

/**
 * generate plain html element
 */
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

/**
 * generate and print html element, use it if you want to print html insetead of returning it
 */
function ph(string $tag, array | null $props = null, string | array ...$children) {
    print h($tag, $props, ...$children);
}


/**
 * generate html table
 * 
 * @param array $data the data to be rendered in the table
 * @param array | null $fields the fields to be rendered in the table, if null all keys in the first row of $data will be used
 * @param array $render an associative array of render functions, the key is the field name and the value is the render function
 * 
 * @return string the rendered html table
 */
function renderTable(array $data, array | null $fields = null, array $render = [])
{
    if (!is_null($fields) && array_is_list($fields)) {
        throw new Exception("\$fields argument must be an associative array");
    }
    $headerFields = [];
    $picked_keys = [];
    if (is_null($fields)) {
        $headerFields = count($data) ? array_keys($data[0]) : [];
        $picked_keys = $headerFields;
    } else {
        $headerFields = array_values($fields);
        $picked_keys = array_keys($fields);
    }

    $headerCols = [];

    foreach ($headerFields as $key) {
        $headerCols[] = h("th", null, $key);
    }

    $thead = h(
        "thead",
        null,
        h("tr", null, $headerCols)
    );

    $rows = [];
    foreach ($data as $row) {
        $cols = [];
        foreach ($picked_keys as $key) {
            $value = $row[$key] ?? "";
            if(in_array($key, array_keys($render))){
                $value = $render[$key]($value);
            }
            $cols[] = h("td", null, $value);
        }
        $rows[] = h("tr", null, ...$cols);
    }

    $tbody = h("tbody", null, $rows);

    // dd($trs);

    return h("table", null, $thead, $tbody);
}

