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
use Mobicms\Captcha\Options;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private $testImage = __DIR__ . '/../test.png';

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
    public function testCanGenerateDataImageString(Image $captcha): void
    {
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @depends testCanCreateInstance
     * @param Image $captcha
     * @throws Exception
     */
    public function testCanGenerateValidImage(Image $captcha): void
    {
        $this->writeImage($captcha->generate());
        $this->assertValidImage(190, 80);
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

    public function testSetCustomFontsFolder(): void
    {
        $options = new Options();
        $options->setFontsFolder(__DIR__);
        $image = (new Image('abcd', $options))->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function testFontsDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The specified folder does not contain any fonts.');
        $options = new Options();
        $options->setFontsFolder(__DIR__ . '/../src');
        new Image('abcd', $options);
    }

    /**
     * @dataProvider customFontValues
     * @param mixed $case
     * @throws Exception
     */
    public function testSetLetterCase($case)
    {
        $options = new Options();
        $options
            ->setFontsFolder(__DIR__)
            ->adjustFont('test.ttf', 32, $case);
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Auxiliary methods                                                          //
    ////////////////////////////////////////////////////////////////////////////////

    private function assertValidImage(int $width, int $height)
    {
        $info = getimagesize($this->testImage);
        $this->assertSame($width, $info[0]);
        $this->assertSame($height, $info[1]);
        $this->assertSame('image/png', $info['mime']);
    }

    private function writeImage(string $image)
    {
        $image = str_replace('data:image/png;base64,', '', $image);
        file_put_contents($this->testImage, base64_decode($image));
    }

    public function customFontValues(): array
    {
        return [
            'RANDOM' => [Options::FONT_CASE_RANDOM],
            'UPPER'  => [Options::FONT_CASE_UPPER],
            'LOWER'  => [Options::FONT_CASE_LOWER],
        ];
    }
}
