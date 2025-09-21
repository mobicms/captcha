<?php

declare(strict_types=1);

use Mobicms\Captcha\Exception\ConfigException;
use Mobicms\Captcha\Exception\FontException;
use Mobicms\Captcha\Image;

describe('Code generation', function () {
    test('returns unique code string', function () {
        $code = (new Image())->getCode();
        $anotherCode = (new Image())->getCode();

        expect(strlen($code))->toBeGreaterThanOrEqual(3)
            ->and(strlen($anotherCode))->toBeGreaterThanOrEqual(3)
            ->and($code)->not->toEqual($anotherCode);
    });

    test('returns provided code and remains same on subsequent calls', function () {
        $image = new Image('ABC123');
        expect($image->getCode())->toBe('ABC123');
        expect($image->getCode())->toBe('ABC123');
    });

    test('generates when empty and respects length bounds', function () {
        $img = new Image('');
        $code = $img->getCode();

        expect($code)->not->toBe('')
            ->and(strlen($code))->toBeGreaterThanOrEqual($img->lengthMin)
            ->toBeLessThanOrEqual($img->lengthMax);
    });

    test('uses valid character set', function () {
        $image = new Image();
        $image->characterSet = 'abc123';
        $code = $image->getCode();
        $validCharacters = str_split($image->characterSet);

        foreach (str_split($code) as $char) {
            expect($validCharacters)->toContain($char);
        }
    });

    test('does not contain excluded combinations', function () {
        $image = new Image();
        $image->excludedCombinationsPattern = 'ab|cd|ef';
        $code = $image->getCode();

        expect(preg_match('/' . $image->excludedCombinationsPattern . '/', $code))->toBe(0);
    });

    test('can specify code length', function () {
        $image = new Image();
        $image->lengthMin = 2;
        $image->lengthMax = 2;
        expect(strlen($image->getCode()))->toEqual(2);

        $image = new Image();
        $image->lengthMin = 5;
        $image->lengthMax = 5;
        expect(strlen($image->getCode()))->toEqual(5);
    });

    test('respects length boundaries', function () {
        $image = new Image();
        $image->lengthMin = 6;
        $image->lengthMax = 8;
        $code = $image->getCode();

        expect(strlen($code))->toBeGreaterThanOrEqual(6);
        expect(strlen($code))->toBeLessThanOrEqual(8);
    });

    test('ability to set your own code character set', function () {
        $image = new Image();
        $image->lengthMin = 3;
        $image->lengthMax = 3;
        $image->characterSet = 'a';
        expect($image->getCode())->toEqual('aaa');
    });

    test('there is no infinite recursion', function () {
        $image = new Image();
        $image->lengthMin = 5;
        $image->lengthMax = 5;
        $image->characterSet = 'm';
        $image->excludedCombinationsPattern = 'm|m';
        expect($image->getCode())->toEqual('mmmmm');
    });
});

describe('Image generation', function () {
    test('can generate valid image', function (string $format) {
        $file = FOLDER . 'test.' . $format;
        $image = new Image('abcd');
        $image->imageFormat = $format;
        file_put_contents($file, $image->build());
        $info = getimagesize($file);
        expect($info[0])->toBe($image->imageWidth)
            ->and($info[1])->toBe($image->imageHeight)
            ->and($info['mime'])->toBe('image/' . $format);
    })->with(['png', 'gif', 'webp']);

    test('can generate data image string', function () {
        expect((new Image('abcd'))->getImage())->toStartWith(DATAIMAGE);
    });

    test('can set custom fonts folder', function () {
        $captcha = new Image('abcd');
        $captcha->fontFolders = [FOLDER];
        expect($captcha->getImage())->toStartWith(DATAIMAGE);
    });

    test('Set letter case', function (int $case) {
        $captcha = new Image('abcd');
        $captcha->fontFolders = [FOLDER];
        $captcha->fontsTune = ['test.ttf' => ['case' => $case]];
        $image = $captcha->getImage();
        expect($image)->toStartWith(DATAIMAGE);
    })->with(
        [
            'random' => [0],
            'upper'  => [Image::FONT_CASE_UPPER],
            'lower'  => [Image::FONT_CASE_LOWER],
        ]
    );

    it('throws an exception on unsupported image format', function () {
        $image = new Image('abcd');
        $image->imageFormat = 'jpg';
        $image->build();
    })->throws(ConfigException::class, 'Unsupported image format');

    it('throws an exception on font folder does not exist', function () {
        $captcha = new Image('abcd');
        $captcha->fontFolders = [__DIR__];
        $captcha->getImage();
    })->throws(FontException::class, 'The specified folder');
});
