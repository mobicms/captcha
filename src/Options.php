<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

declare(strict_types=1);

namespace Mobicms\Captcha;

use InvalidArgumentException;

use function pathinfo;

class Options
{
    /** @var array */
    private $options = [
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

    public function getOptionsArray(): array
    {
        return $this->options;
    }

    public function setImageSize(int $width, int $height): self
    {
        if ($width < 20 || $height < 20) {
            throw new InvalidArgumentException('Image size cannot be less than 20x20px');
        }

        $this->options['image_width'] = $width;
        $this->options['image_height'] = $height;
        return $this;
    }

    public function setFontShuffle(bool $shuffle): self
    {
        $this->options['fonts_shuffle'] = $shuffle;
        return $this;
    }

    public function setDefaultFontSize(int $size): self
    {
        if ($size <= 0) {
            throw new InvalidArgumentException('You specified the wrong font size.');
        }

        $this->options['fonts_size'] = $size;
        return $this;
    }

    public function setFontsFolder(string $folder): self
    {
        if (! is_dir($folder)) {
            throw new InvalidArgumentException('The specified folder does not exist.');
        }

        $this->options['fonts_folder'] = $folder;
        return $this;
    }

    public function adjustFont(
        string $fontName,
        int $size,
        int $case = self::FONT_CASE_RANDOM
    ): self {
        if (pathinfo($fontName, PATHINFO_EXTENSION) !== 'ttf') {
            throw new InvalidArgumentException('The font file must be with the extension .ttf');
        }

        $this->options['fonts_tuning'][$fontName] = [
            'size' => $size,
            'case' => $case,
        ];
        return $this;
    }
}
