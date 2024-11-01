<?php

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use LogicException;
use Mobicms\Captcha\Image;
use Mobicms\Captcha\ImageOptions;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private const FOLDER = __DIR__ . '/../stubs/';
    private const DATAIMAGE = 'data:image/png;base64';

    private Image $imageObj;

    public function setUp(): void
    {
        $this->imageObj = new Image('abcd');
    }

    /**
     * @throws \Exception
     */
    public function testCanGenerateDataImageString(): void
    {
        $image = $this->imageObj->generate();
        $this->assertStringStartsWith(self::DATAIMAGE, $image);
    }

    /**
     * @throws \Exception
     */
    public function testCanGenerateValidImage(): void
    {
        $this->writeImage($this->imageObj->generate());
        $info = getimagesize(self::FOLDER . 'test.png');
        $this->assertSame(190, $info[0]);
        $this->assertSame(80, $info[1]);
        $this->assertSame('image/png', $info['mime']);
    }

    public function testToString(): void
    {
        $image = (string) $this->imageObj;
        $this->assertStringStartsWith(self::DATAIMAGE, $image);
    }

    /**
     * @throws \Exception
     */
    public function testSetCustomFontsFolder(): void
    {
        $options = new ImageOptions();
        $options->setFontsFolder(self::FOLDER);
        $image = (new Image('abcd', $options))->generate();
        $this->assertStringStartsWith(self::DATAIMAGE, $image);
    }

    public function testFontsDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The specified folder does not contain any fonts.');
        $options = new ImageOptions();
        $options->setFontsFolder(__DIR__);
        new Image('abcd', $options);
    }

    /**
     * @dataProvider customFontValues
     * @throws \Exception
     */
    public function testSetLetterCase(int $case): void
    {
        $options = new ImageOptions();
        $options
            ->setFontsFolder(self::FOLDER)
            ->adjustFont('test.ttf', 32, $case);
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith(self::DATAIMAGE, $image);
    }

    private function writeImage(string $image): void
    {
        $image = str_replace(self::DATAIMAGE, '', $image);
        file_put_contents(self::FOLDER . 'test.png', base64_decode($image));
    }

    /**
     * @return array<array<int>>
     */
    public static function customFontValues(): array
    {
        return [
            'RANDOM' => [0],
            'UPPER'  => [ImageOptions::FONT_CASE_UPPER],
            'LOWER'  => [ImageOptions::FONT_CASE_LOWER],
        ];
    }
}
