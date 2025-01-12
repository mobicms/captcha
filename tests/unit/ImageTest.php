<?php

declare(strict_types=1);

use Mobicms\Captcha\Image;

const FOLDER = __DIR__ . '/../stubs/';
const DATAIMAGE = 'data:image/png;base64';

test('Can generate data image string', function () {
    expect((new Image('abcd'))->generate())->toStartWith(DATAIMAGE);
});

test('Can generate valid image', function () {
    writeImage((new Image('abcd'))->generate());
    $info = getimagesize(FOLDER . 'test.png');
    expect($info[0])->toBe(190);
    expect($info[1])->toBe(80);
    expect($info['mime'])->toBe('image/png');
});

test('Can set custom fonts folder', function () {
    $captcha = (new Image('abcd'));
    $captcha->fontFolders = [FOLDER];
    expect($captcha->generate())->toStartWith(DATAIMAGE);
});

test('If the font folder does not exist', function () {
    $captcha = new Image('abcd');
    $captcha->fontFolders = [__DIR__];
    $captcha->generate();
})->throws(LogicException::class);

test('set letter case', function (int $case) {
    $captcha = new Image('abcd');
    $captcha->fontFolders = [FOLDER];
    $captcha->fontsTune = ['test.ttf' => ['case' => $case]];
    $image = $captcha->generate();
    expect($image)->toStartWith(DATAIMAGE);
})->with('customFontValues');

dataset('customFontValues', function () {
    return [
        'random' => [0],
        'upper'  => [Image::FONT_CASE_UPPER],
        'lower'  => [Image::FONT_CASE_LOWER],
    ];
});

// phpcs:disable
function writeImage(string $image): void
{
    $image = str_replace(DATAIMAGE, '', $image);
    file_put_contents(FOLDER . 'test.png', base64_decode($image));
}
// phpcs:enable
