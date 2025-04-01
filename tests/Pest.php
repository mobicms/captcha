<?php

declare(strict_types=1);

const FOLDER = __DIR__ . '/stubs/';
const DATAIMAGE = 'data:image/png;base64';

function writeImage(string $image): void
{
    $image = str_replace(DATAIMAGE, '', $image);
    file_put_contents(FOLDER . 'test.png', base64_decode($image));
}
