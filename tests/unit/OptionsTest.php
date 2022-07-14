<?php

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use InvalidArgumentException;
use Mobicms\Captcha\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    private Options $options;

    public function setUp(): void
    {
        $this->options = new Options();
    }

    public function testGetImageHeight(): void
    {
        $this->assertSame(80, $this->options->getImageHeight());
    }

    public function testSetImageHeight(): void
    {
        $this->options->setImageHeight(100);
        $this->assertSame(100, $this->options->getImageHeight());
    }

    public function testSetImageHeightInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->setImageHeight(1);
    }

    public function testGetImageWidth(): void
    {
        $this->assertSame(190, $this->options->getImageWidth());
    }

    public function testSetImageWidth(): void
    {
        $this->options->setImageWidth(100);
        $this->assertSame(100, $this->options->getImageWidth());
    }

    public function testSetImageWidthInvalidValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->setImageWidth(1);
    }

    public function testGetFontsFolder(): void
    {
        $this->assertStringEndsWith('fonts', $this->options->getFontsFolder());
    }

    public function testSetFontsFolder(): void
    {
        $this->options->setFontsFolder(__DIR__);
        $this->assertSame(__DIR__, $this->options->getFontsFolder());
    }

    public function testInvalidFontsFolder(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The specified folder does not exist.');
        $this->options->setFontsFolder('invalid_folder');
    }

    public function testGetFontShuffle(): void
    {
        $this->assertIsBool($this->options->getFontShuffle());
    }

    public function testSetFontShuffle(): void
    {
        $this->options->setFontShuffle(false);
        $this->assertFalse($this->options->getFontShuffle());
        $this->options->setFontShuffle(true);
        $this->assertTrue($this->options->getFontShuffle());
    }

    public function testSetDefaultFontSize(): void
    {
        $this->options->setDefaultFontSize(40);
        $this->assertSame(40, $this->options->getFontSize());
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
        $this->options->adjustFont($font, 40, Options::FONT_CASE_UPPER);
        $this->assertSame(40, $this->options->getFontSize($font));
        $this->assertSame(Options::FONT_CASE_UPPER, $this->options->getFontCase($font));
    }

    public function testInvalidFontName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->adjustFont('somefont.jpg', 27, Options::FONT_CASE_UPPER);
    }
}
