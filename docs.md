




# Functions

## `utils/html.php`

### h($tag, $props, $children)
generate plain html
```php
echo h("ul", null, 
    h("li", null, "Item 1"),
    h("li", null, "Item 2"),
    h("li", null, "Item 3")
); // Output: <ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>
```

### renderTable(array $data, array | null $fields = null, array $render = [])
generate html table
```php

$data = [
    [
        'name' => "Aya",
        'level' => 3
    ],
    [
        'name' => "Jana",
        'level' => 3
    ],
    [
        'name' => "Mohammed",
        'level' => 1
    ],
    [
        'name' => "Osama",
        'level' => 3
    ]
];

//by using this select only specific columns from data (also set custom title for columns)
$cols = [
    'name' => 'Name',
    'level' => 'Game Level'
];

generateTable($data, $cols /* <- defaults to all columns */);
```