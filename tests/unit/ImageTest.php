<?php

declare(strict_types=1);

use Mobicms\Captcha\Image;
use Mobicms\Captcha\ImageOptions;

const FOLDER = __DIR__ . '/../stubs/';
const DATAIMAGE = 'data:image/png;base64';

test('Can generate data image string', function () {
    expect((string) new Image('abcd'))->toStartWith(DATAIMAGE);
});

test('Can generate valid image', function () {
    writeImage((string) new Image('abcd'));
    $info = getimagesize(FOLDER . 'test.png');
    expect($info[0])->toBe(190);
    expect($info[1])->toBe(80);
    expect($info['mime'])->toBe('image/png');
});

test('Can set custom fonts folder', function () {
    $options = new ImageOptions();
    $options->setFontsFolder(FOLDER);
    $image = (new Image('abcd', $options));
    expect((string) $image)->toStartWith(DATAIMAGE);
});

test('Fonts does not exist', function () {
    $options = new ImageOptions();
    $options->setFontsFolder(__DIR__);
    new Image('abcd', $options);
})->throws(LogicException::class, 'The specified folder does not contain any fonts.');

test('set letter case', function (int $case) {
    $options = new ImageOptions();
    $options
        ->setFontsFolder(FOLDER)
        ->adjustFont('test.ttf', 32, $case);
    $captcha = new Image('abcd', $options);
    $image = $captcha->generate();
    expect($image)->toStartWith(DATAIMAGE);
})->with('customFontValues');

dataset('customFontValues', function () {
    return [
        'random' => [0],
        'upper'  => [ImageOptions::FONT_CASE_UPPER],
        'lower'  => [ImageOptions::FONT_CASE_LOWER],
    ];
});

// phpcs:disable
function writeImage(string $image): void
{
    $image = str_replace(DATAIMAGE, '', $image);
    file_put_contents(FOLDER . 'test.png', base64_decode($image));
}
// phpcs:enable
