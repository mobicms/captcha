<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/mobicms/captcha
 */

declare(strict_types=1);

namespace Mobicms\Captcha;

class Configuration
{
    protected int $imageHeight = 80;
    protected int $imageWidth = 190;
    protected string $fontsFolder = __DIR__ . '/../resources/fonts';
    protected bool $fontShuffle = true;
    protected int $defaultFontSize = 26;

    /**
     * @var array<string, array<string, int>>
     */
    protected array $fontsConfiguration = [
        '3dlet.ttf' => [
            'size' => 38,
            'case' => self::FONT_CASE_LOWER,
        ],

        'baby_blocks.ttf' => [
            'size' => 16,
            'case' => self::FONT_CASE_RANDOM,
        ],

        'betsy_flanagan.ttf' => [
            'size' => 30,
            'case' => self::FONT_CASE_RANDOM,
        ],

        'karmaticarcade.ttf' => [
            'size' => 20,
            'case' => self::FONT_CASE_RANDOM,
        ],

        'tonight.ttf' => [
            'size' => 28,
            'case' => self::FONT_CASE_RANDOM,
        ],
    ];

    public const FONT_CASE_UPPER = 2;
    public const FONT_CASE_LOWER = 1;
    public const FONT_CASE_RANDOM = 0;

    public function getImageHeight(): int
    {
        return $this->imageHeight;
    }

    public function getImageWidth(): int
    {
        return $this->imageWidth;
    }

    public function getFontsFolder(): string
    {
        return realpath($this->fontsFolder);
    }

    public function getFontShuffle(): bool
    {
        return $this->fontShuffle;
    }

    public function getDefaultFontSize(): int
    {
        return $this->defaultFontSize;
    }

    /**
     * @return array<string, array<string, int>>
     */
    public function getFontsConfiguration(): array
    {
        return $this->fontsConfiguration;
    }
}
