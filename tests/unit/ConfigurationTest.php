<?php

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

    public function testGetFontsConfiguration(): void
    {
        $config = $this->config->getFontsConfiguration();
        $this->assertArrayHasKey('baby_blocks.ttf', $config);
    }
}
