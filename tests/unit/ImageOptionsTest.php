<?php

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use InvalidArgumentException;
use Mobicms\Captcha\ImageOptions;
use PHPUnit\Framework\TestCase;

class ImageOptionsTest extends TestCase
{
    private ImageOptions $options;

    public function setUp(): void
    {
        $this->options = new ImageOptions();
    }

    public function testGetImageHeight(): void
    {
        self::assertSame(80, $this->options->getHeight());
    }

    public function testSetImageHeight(): void
    {
        $this->options->setHeight(100);
        self::assertSame(100, $this->options->getHeight());
    }

    public function testSetImageHeightInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->setHeight(1);
    }

    public function testGetImageWidth(): void
    {
        self::assertSame(190, $this->options->getWidth());
    }

    public function testSetImageWidth(): void
    {
        $this->options->setWidth(100);
        self::assertSame(100, $this->options->getWidth());
    }

    public function testSetImageWidthInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->setWidth(1);
    }

    public function testGetFontsFolder(): void
    {
        self::assertStringEndsWith('fonts', $this->options->getFontsFolder());
    }

    public function testSetFontsFolder(): void
    {
        $this->options->setFontsFolder(__DIR__);
        self::assertSame(__DIR__, $this->options->getFontsFolder());
    }

    public function testInvalidFontsFolder(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The specified folder does not exist.');
        $this->options->setFontsFolder('invalid_folder');
    }

    public function testGetFontShuffle(): void
    {
        self::assertTrue($this->options->getFontShuffle());
    }

    public function testSetFontShuffle(): void
    {
        $this->options->setFontShuffle(false);
        self::assertFalse($this->options->getFontShuffle());
        $this->options->setFontShuffle(true);
        self::assertTrue($this->options->getFontShuffle());
    }

    public function testSetDefaultFontSize(): void
    {
        $this->options->setDefaultFontSize(40);
        self::assertSame(40, $this->options->getFontSize());
    }

    public function testInvalidDefaultFontSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You specified the wrong font size.');
        $this->options->setDefaultFontSize(1);
    }

    public function testAdjustFont(): void
    {
        $font = 'somefont.ttf';
        $this->options->adjustFont($font, 40, ImageOptions::FONT_CASE_UPPER);
        self::assertSame(40, $this->options->getFontSize($font));
        self::assertSame(ImageOptions::FONT_CASE_UPPER, $this->options->getFontCase($font));
    }

    public function testInvalidFontName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->adjustFont('somefont.jpg', 27, ImageOptions::FONT_CASE_UPPER);
    }
}
