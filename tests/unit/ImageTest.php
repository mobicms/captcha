<?php

declare(strict_types=1);

use Mobicms\Captcha\Image;

const FOLDER = __DIR__ . '/../stubs/';
const DATAIMAGE = 'data:image/png;base64';

test('Can generate unique code string', function () {
    $code = (new Image())->getCode();
    $anotherCode = (new Image())->getCode();

    expect(strlen($code))->toBeGreaterThanOrEqual(3);
    expect(strlen($anotherCode))->toBeGreaterThanOrEqual(3);
    expect($code)->not->toEqual($anotherCode);
});

test('Can specify code length', function () {
    $image = new Image();
    $image->lengthMin = 2;
    $image->lengthMax = 2;
    expect(strlen($image->getCode()))->toEqual(2);

    $image = new Image();
    $image->lengthMin = 5;
    $image->lengthMax = 5;
    expect(strlen($image->getCode()))->toEqual(5);
});

test('Ability to set your own code character set', function () {
    $image = new Image();
    $image->lengthMin = 3;
    $image->lengthMax = 3;
    $image->characterSet = 'a';
    expect($image->getCode())->toEqual('aaa');
});

test('Can generate data image string', function () {
    expect((new Image('abcd'))->getImage())->toStartWith(DATAIMAGE);
});

test('Can generate valid image', function () {
    $image = new Image('abcd');
    writeImage($image->getImage());
    $info = getimagesize(FOLDER . 'test.png');
    expect($info[0])->toBe($image->imageWidth);
    expect($info[1])->toBe($image->imageHeight);
    expect($info['mime'])->toBe('image/png');
});

test('Can set custom fonts folder', function () {
    $captcha = (new Image('abcd'));
    $captcha->fontFolders = [FOLDER];
    expect($captcha->getImage())->toStartWith(DATAIMAGE);
});

test('If the font folder does not exist', function () {
    $captcha = new Image('abcd');
    $captcha->fontFolders = [__DIR__];
    $captcha->getImage();
})->throws(LogicException::class);

test('set letter case', function (int $case) {
    $captcha = new Image('abcd');
    $captcha->fontFolders = [FOLDER];
    $captcha->fontsTune = ['test.ttf' => ['case' => $case]];
    $image = $captcha->getImage();
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
