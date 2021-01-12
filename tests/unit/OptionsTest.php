<?php

/**
 * This file is part of mobicms/captcha package
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/mobicms/captcha
 */

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
        $this->assertSame(40, $this->options->getDefaultFontSize());
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
        $config = $this->options->getFontsConfiguration();
        $this->assertArrayHasKey($font, $config);
        $this->assertSame(40, $config[$font]['size']);
        $this->assertSame(Options::FONT_CASE_UPPER, $config[$font]['case']);
    }

    public function testInvalidFontName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->options->adjustFont('somefont.jpg', 27, Options::FONT_CASE_UPPER);
    }
}
