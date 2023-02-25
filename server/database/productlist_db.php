<?php

declare(strict_types=1);

function productlistDB($db)
{
    return "
        CREATE DATABASE IF NOT EXISTS {$db};
    ";
}
