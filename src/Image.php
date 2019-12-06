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

use Exception;
use LogicException;

class Image
{
    private $code;

    private $fontList = [];

    private $options = [
        'image_width'     => 160,
        'image_height'    => 60,
        'fonts_directory' => __DIR__ . '/../resources/fonts',
        'fonts_shuffle'   => true,
        'fonts_size'      => 24,
        'fonts_tuning'    => [
            '3dlet.ttf' => [
                'size' => 32,
                'case' => self::FONT_CASE_LOWER,
            ],

            'baby_blocks.ttf' => [
                'size' => 16,
                'case' => self::FONT_CASE_RANDOM,
            ],

            'betsy_flanagan.ttf' => [
                'size' => 28,
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

    public function __construct(string $code, array $options = [])
    {
        $this->code = $code;
        $this->options = array_replace_recursive($this->options, $options);
        $fontList = glob(realpath($this->options['fonts_directory']) . DIRECTORY_SEPARATOR . '*.ttf');

        if ([] === $fontList || false === $fontList) {
            throw new LogicException('The fonts you specified do not exist.');
        }

        $this->fontList = $fontList;
    }

    /**
     * @throws Exception
     */
    public function __toString(): string
    {
        return $this->generate();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generate(): string
    {
        ob_start();
        $image = imagecreatetruecolor($this->options['image_width'], $this->options['image_height']);

        if ($image !== false) {
            imagesavealpha($image, true);
            imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
            $this->drawTextOnImage($image);
            imagepng($image);
            imagedestroy($image);
        }

        return 'data:image/png;base64,' . base64_encode(ob_get_clean());
    }

    /**
     * Drawing the text on the image
     *
     * @param       $image
     * @throws Exception
     */
    private function drawTextOnImage(&$image): void
    {
        $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
        $code = str_split($this->code);
        $len = count($code);

        for ($i = 0; $i < $len; $i++) {
            if ($this->options['fonts_shuffle']) {
                $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
            }

            $fontName = basename($font);
            $letter = $this->setLetterCase($code[$i], $font);
            $fontSize = $this->determineFontSize($fontName);
            $xPos = ($this->options['image_width'] - $fontSize) / $len * $i + ($fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->options['image_height'] - (($this->options['image_height'] - $fontSize) / 2);
            $capcolor = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));
            $capangle = random_int(-25, 25);
            imagettftext($image, $fontSize, $capangle, $xPos, $yPos, $capcolor, $font, $letter);
        }
    }

    private function determineFontSize(string $fontName): int
    {
        return isset($this->options['fonts_tuning'][$fontName])
            ? (int) $this->options['fonts_tuning'][$fontName]['size']
            : (int) $this->options['fonts_size'];
    }

    private function setLetterCase(string $string, string $fontName): string
    {
        $font = basename($fontName);

        if (isset($this->options['fonts_tuning'][$font])) {
            switch ($this->options['fonts_tuning'][$font]['case']) {
                case 2:
                    return strtoupper($string);
                case 1:
                    return strtolower($string);
            }
        }

        return $string;
    }
}
