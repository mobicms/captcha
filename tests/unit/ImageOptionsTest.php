<?php

declare(strict_types=1);

use Mobicms\Captcha\ImageOptions;

beforeEach(function () {
    $this->imageOptions = new ImageOptions();
});

test('Default configuration', function () {
    expect($this->imageOptions)
        ->getWidth()->toBe(190)
        ->getHeight()->toBe(80)
        ->getFontsFolder()->toEndWith('fonts')
        ->getFontShuffle()->toBeTrue()
        ->getFontSize()->toBe(26);
});

test('Set image width', function () {
    $this->imageOptions->setWidth(100);
    expect($this->imageOptions->getWidth())->toBe(100);

    // Trying to set an invalid value
    $this->imageOptions->setWidth(1);
})->throws(InvalidArgumentException::class);

test('Set image heigth', function () {
    $this->imageOptions->setHeight(100);
    expect($this->imageOptions->getHeight())->toBe(100);

    // Trying to set an invalid value
    $this->imageOptions->setHeight(1);
})->throws(InvalidArgumentException::class);

test('Set fonts folder', function () {
    $this->imageOptions->setFontsFolder(__DIR__);
    expect($this->imageOptions->getFontsFolder())->toBe(__DIR__);

    // Trying to set an invalid value
    $this->imageOptions->setFontsFolder('invalid_folder');
})->throws(InvalidArgumentException::class);

test('Set default font size', function () {
    $this->imageOptions->setDefaultFontSize(40);
    expect($this->imageOptions->getFontSize())->toBe(40);

    // Trying to set an invalid value
    $this->imageOptions->setDefaultFontSize(1);
})->throws(InvalidArgumentException::class);

test('Font shuffle on/off', function () {
    $this->imageOptions->setFontShuffle(false);
    expect($this->imageOptions->getFontShuffle())->toBeFalse();

    $this->imageOptions->setFontShuffle(true);
    expect($this->imageOptions->getFontShuffle())->toBeTrue();
});

test('Set custom font options', function () {
    $font = 'somefont.ttf';
    $this->imageOptions->adjustFont($font, 40, ImageOptions::FONT_CASE_UPPER);
    expect($this->imageOptions->getFontSize($font))->toBe(40);
    expect($this->imageOptions->getFontCase($font))->toBe(ImageOptions::FONT_CASE_UPPER);

    // Trying to set an invalid value
    $this->imageOptions->adjustFont('somefont.jpg', 27, ImageOptions::FONT_CASE_UPPER);
})->throws(InvalidArgumentException::class, 'The font file must be with the extension .ttf');
