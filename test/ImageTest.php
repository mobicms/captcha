<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use Exception;
use LogicException;
use Mobicms\Captcha\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testCanCreateInstance(): Image
    {
        $captcha = new Image('abcd');
        $this->assertInstanceOf(Image::class, $captcha);
        return $captcha;
    }

    /**
     * @depends testCanCreateInstance
     * @param Image $captcha
     * @throws Exception
     */
    public function testCanGenerateImage(Image $captcha): void
    {
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @depends testCanCreateInstance
     * @param Image $captcha
     */
    public function testToString(Image $captcha): void
    {
        $image = (string) $captcha;
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function testCanSetCustomFontsFolder(): void
    {
        $options = ['fonts_directory' => __DIR__];
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function testInvalidFolderOrFontsDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The fonts you specified do not exist.');
        $options = ['fonts_directory' => 'invalid_folder'];
        new Image('abcd', $options);
    }

    /**
     * @dataProvider customFontValues
     * @throws Exception
     */
    public function testSetLetterCase($case)
    {
        $options = [
            'fonts_directory' => __DIR__,
            'fonts_tuning'    => [
                'test.ttf' => [
                    'size' => 32,
                    'case' => $case,
                ],
            ],
        ];
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function customFontValues(): array
    {
        return [
            'RANDOM' => [Image::FONT_CASE_RANDOM],
            'UPPER'  => [Image::FONT_CASE_UPPER],
            'LOWER'  => [Image::FONT_CASE_LOWER],
        ];
    }
}
