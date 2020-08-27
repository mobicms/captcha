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
    /**
     * @deprecated
     * @var array<string, mixed>
     */
    protected array $options = [
        'image_width'   => 190,
        'image_height'  => 80,
        'fonts_folder'  => __DIR__ . '/../resources/fonts',
        'fonts_shuffle' => true,
        'fonts_size'    => 26,
        'fonts_tuning'  => [
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
        ],
    ];

    public const FONT_CASE_UPPER = 2;
    public const FONT_CASE_LOWER = 1;
    public const FONT_CASE_RANDOM = 0;

    /**
     * @deprecated
     * @return array<string, mixed>
     */
    public function getOptionsArray(): array
    {
        return $this->options;
    }
}
