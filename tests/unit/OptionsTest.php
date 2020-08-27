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

use InvalidArgumentException;
use Mobicms\Captcha\Options;
use PHPUnit\Framework\TestCase;

class OptionsTest extends TestCase
{
    /** @var Options */
    private $oprions;

    public function setUp(): void
    {
        $this->oprions = new Options();
    }

    public function testGetOptionsArray(): void
    {
        $this->assertIsArray($this->oprions->getOptionsArray());
    }

    public function testSetImageSize(): void
    {
        $this->oprions->setImageSize(27, 27);
        $options = $this->oprions->getOptionsArray();
        $this->assertSame(27, $options['image_width']);
        $this->assertSame(27, $options['image_height']);
    }

    public function testSetInvalidImageSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Image size cannot be less than');
        $this->oprions->setImageSize(0, 1);
    }

    public function testSetFontShuffle(): void
    {
        $this->oprions->setFontShuffle(false);
        $options = $this->oprions->getOptionsArray();
        $this->assertFalse($options['fonts_shuffle']);
    }

    public function testSetDefaultFontSize(): void
    {
        $this->oprions->setDefaultFontSize(40);
        $options = $this->oprions->getOptionsArray();
        $this->assertSame(40, $options['fonts_size']);
    }

    public function testInvalidDefaultFontSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('You specified the wrong font size.');
        $this->oprions->setDefaultFontSize(-5);
    }

    public function testSetFontsFolder(): void
    {
        $this->oprions->setFontsFolder(__DIR__);
        $options = $this->oprions->getOptionsArray();
        $this->assertSame(__DIR__, $options['fonts_folder']);
    }

    public function testInvalidFontsFolder(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The specified folder does not exist.');
        $this->oprions->setFontsFolder('invalid_folder');
    }

    public function testAdjustFont(): void
    {
        $font = 'somefont.ttf';
        $this->oprions->adjustFont($font, 40, Options::FONT_CASE_UPPER);
        $options = $this->oprions->getOptionsArray();
        $this->assertArrayHasKey($font, $options['fonts_tuning']);
        $this->assertSame(40, $options['fonts_tuning'][$font]['size']);
        $this->assertSame(Options::FONT_CASE_UPPER, $options['fonts_tuning'][$font]['case']);
    }

    public function testInvalidFontName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->oprions->adjustFont('somefont.jpg', 27, Options::FONT_CASE_UPPER);
    }
}
