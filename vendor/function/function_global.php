<?php
function showLoader(): string
{
    return '<div class="loader"></div alt="LOADER">';
}

function redirect($url = '/')
{
    header('Location: '.$url);
    die();
}
