<?php

declare(strict_types=1);

use Mobicms\Captcha\Image;

describe('Code generation', function () {
    test('Can generate unique code string', function () {
        $code = (new Image())->getCode();
        $anotherCode = (new Image())->getCode();

        expect(strlen($code))->toBeGreaterThanOrEqual(3)
            ->and(strlen($anotherCode))->toBeGreaterThanOrEqual(3)
            ->and($code)->not->toEqual($anotherCode);
    });

    test('Generated code uses valid character set', function () {
        $image = new Image();
        $image->characterSet = 'abc123';
        $code = $image->getCode();
        $validCharacters = str_split($image->characterSet);

        foreach (str_split($code) as $char) {
            expect($validCharacters)->toContain($char);
        }
    });

    test('Generated code does not contain excluded combinations', function () {
        $image = new Image();
        $image->excludedCombinationsPattern = 'ab|cd|ef';
        $code = $image->getCode();

        expect(preg_match('/' . $image->excludedCombinationsPattern . '/', $code))->toBe(0);
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

    test('Generated code respects length boundaries', function () {
        $image = new Image();
        $image->lengthMin = 6;
        $image->lengthMax = 8;
        $code = $image->getCode();

        expect(strlen($code))->toBeGreaterThanOrEqual(6);
        expect(strlen($code))->toBeLessThanOrEqual(8);
    });

    test('Ability to set your own code character set', function () {
        $image = new Image();
        $image->lengthMin = 3;
        $image->lengthMax = 3;
        $image->characterSet = 'a';
        expect($image->getCode())->toEqual('aaa');
    });
});

describe('Image generation', function () {
    test('Can generate data image string', function () {
        expect((new Image('abcd'))->getImage())->toStartWith(DATAIMAGE);
    });

    test('Can generate valid image', function () {
        $image = new Image('abcd');
        writeImage($image->getImage());
        $info = getimagesize(FOLDER . 'test.png');
        expect($info[0])->toBe($image->imageWidth)
            ->and($info[1])->toBe($image->imageHeight)
            ->and($info['mime'])->toBe('image/png');
    });

    test('Can generate valid binary string', function () {
        $captcha = new Image('abcd');
        $data = $captcha->build();

        expect($data)->toBeString()
            ->and(substr($data, 0, 8))->toEqual("\x89PNG\x0d\x0a\x1a\x0a");
    });

    test('Can set custom fonts folder', function () {
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

    it('throws an exception on font folder does not exist', function () {
        $captcha = new Image('abcd');
        $captcha->fontFolders = [__DIR__];
        $captcha->getImage();
    })->throws(LogicException::class);
});
