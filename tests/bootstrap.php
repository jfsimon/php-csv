<?php

spl_autoload_register(function ($className) {
    $file = realpath(
        __DIR__.DIRECTORY_SEPARATOR
        .'..'.DIRECTORY_SEPARATOR
        .'lib'.DIRECTORY_SEPARATOR
        .str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php'
    );

    if (!file_exists($file)) {
        throw new \InvalidArgumentException('Could not load class: '.$className);
    }

    require_once $file;
});
