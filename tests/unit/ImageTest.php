<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/mobicms/captcha
 */

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use Exception;
use LogicException;
use Mobicms\Captcha\Configuration;
use Mobicms\Captcha\Image;
use Mobicms\Captcha\Options;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private string $testImage = TEST_OUT_PATH . 'test.png';
    private Image $image;

    public function setUp(): void
    {
        $this->image = new Image('abcd');
    }

    /**
     * @throws Exception
     */
    public function testCanGenerateDataImageString(): void
    {
        $image = $this->image->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @throws Exception
     */
    public function testCanGenerateValidImage(): void
    {
        $this->writeImage($this->image->generate());
        $this->assertValidImage(190, 80);
    }

    public function testToString(): void
    {
        $image = (string) $this->image;
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @throws Exception
     */
    public function testSetCustomFontsFolder(): void
    {
        $options = new Options();
        $options->setFontsFolder(TEST_FONTS_PATH);
        $image = (new Image('abcd', $options))->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function testFontsDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The specified folder does not contain any fonts.');
        $options = new Options();
        $options->setFontsFolder(__DIR__);
        new Image('abcd', $options);
    }

    /**
     * @dataProvider customFontValues
     * @param int $case
     * @throws Exception
     */
    public function testSetLetterCase(int $case): void
    {
        $options = new Options();
        $options
            ->setFontsFolder(TEST_FONTS_PATH)
            ->adjustFont('test.ttf', 32, $case);
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Auxiliary methods                                                          //
    ////////////////////////////////////////////////////////////////////////////////

    private function assertValidImage(int $width, int $height): void
    {
        $info = getimagesize($this->testImage);
        $this->assertSame($width, $info[0]);
        $this->assertSame($height, $info[1]);
        $this->assertSame('image/png', $info['mime']);
    }

    private function writeImage(string $image): void
    {
        $image = str_replace('data:image/png;base64,', '', $image);
        file_put_contents($this->testImage, base64_decode($image));
    }

    /**
     * @return array<string, array<int>>
     */
    public function customFontValues(): array
    {
        return [
            'RANDOM' => [Configuration::FONT_CASE_RANDOM],
            'UPPER'  => [Configuration::FONT_CASE_UPPER],
            'LOWER'  => [Configuration::FONT_CASE_LOWER],
        ];
    }
}
