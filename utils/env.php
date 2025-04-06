<?php

function loadEnv(string | null $path = null)
{
    $env = file_get_contents($path ?? ".env");
    $lines = explode("\n", $env);

    foreach ($lines as $line) {
        preg_match("/([^#]+)\=(.*)/", $line, $matches);
        if (isset($matches[2])) {
            $trimmedValue = trim($matches[2], " \n\r\t\v\0\'\"");
            $safe = "$matches[1]=$trimmedValue";
            putenv(trim($safe));
        }
    }
}
