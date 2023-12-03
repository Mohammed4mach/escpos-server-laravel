<?php

function getGlobal($key)
{
    return env($key);
}

function setGlobal($key, $value)
{
    $oldVal = env($key);

    file_put_contents(app()->environmentFilePath(), str_replace(
        $key . '=' . $oldVal,
        $key . '=' . $value,
        file_get_contents(app()->environmentFilePath())
    ));
}


