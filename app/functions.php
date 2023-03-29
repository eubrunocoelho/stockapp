<?php

function dd($p = [], $die = false)
{
    echo '<pre>';
    var_dump($p);
    echo '</pre>';

    if ($die) die();
}
