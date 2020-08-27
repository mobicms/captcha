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

use Mobicms\Captcha\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    private Configuration $config;

    public function setUp(): void
    {
        $this->config = new Configuration();
    }

    public function testGetImageHeight(): void
    {
        $this->assertSame(80, $this->config->getImageHeight());
    }

    public function testGetImageWidth(): void
    {
        $this->assertSame(190, $this->config->getImageWidth());
    }

    public function testGetFontsFolder(): void
    {
        $this->assertStringEndsWith('fonts', $this->config->getFontsFolder());
    }

    public function testGetFontShuffle(): void
    {
        $this->assertIsBool($this->config->getFontShuffle());
    }

    public function testGetDefaultFontSize(): void
    {
        $this->assertSame(26, $this->config->getDefaultFontSize());
    }

    public function testGetFontsConfiguration(): void
    {
        $config = $this->config->getFontsConfiguration();
        $this->assertIsArray($config);
        $this->assertArrayHasKey('baby_blocks.ttf', $config);
    }
}
