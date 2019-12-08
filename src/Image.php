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
    /** @var string */
    private $code;

    /** @var array */
    private $fontList;

    /** @var array */
    private $options;

    public function __construct(string $code, Options $options = null)
    {
        $this->code = $code;
        $optionsObj = $options ?? new Options();
        $this->options = $optionsObj->getOptionsArray();
        $fontList = glob(realpath($this->options['fonts_folder']) . DIRECTORY_SEPARATOR . '*.ttf');

        if ([] === $fontList || false === $fontList) {
            throw new LogicException('The specified folder does not contain any fonts.');
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
            $color = imagecolorallocatealpha($image, 0, 0, 0, 127);

            if ($color !== false) {
                imagesavealpha($image, true);
                imagefill($image, 0, 0, $color);
                $this->drawTextOnImage($image);
                imagepng($image);
                imagedestroy($image);
            }
        }

        $out = ob_get_clean();
        return 'data:image/png;base64,' . base64_encode((string) $out);
    }

    /**
     * Drawing the text on the image
     *
     * @param resource $image
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
            $letter = $this->setLetterCase($code[$i], $fontName);
            $fontSize = $this->determineFontSize($fontName);
            $xPos = ($this->options['image_width'] - $fontSize) / $len * $i + ($fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->options['image_height'] - (($this->options['image_height'] - $fontSize) / 2);
            $capangle = random_int(-25, 25);
            $capcolor = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));

            if ($capcolor !== false) {
                imagettftext($image, $fontSize, $capangle, $xPos, (int) $yPos, $capcolor, $font, $letter);
            }
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
        if (isset($this->options['fonts_tuning'][$fontName])) {
            switch ($this->options['fonts_tuning'][$fontName]['case']) {
                case Options::FONT_CASE_UPPER:
                    return strtoupper($string);
                case Options::FONT_CASE_LOWER:
                    return strtolower($string);
            }
        }

        return $string;
    }
}
