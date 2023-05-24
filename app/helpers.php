<?php

function prettifyJson($state, $component)
{
    $jsonString = json_encode($state);
    $jsonString = stripslashes($jsonString);
    $jsonString = preg_replace('/,(?!})/', ",\n", $jsonString);
    $jsonString = preg_replace('/:/', ": ", $jsonString);
    $jsonString = str_replace('{', "{\n", $jsonString);
    $jsonString = str_replace('}', "\n}", $jsonString);

    return $component->state($jsonString);
}
