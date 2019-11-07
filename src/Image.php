<?php

declare(strict_types=1);

/**
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

namespace Batumibiz\Captcha;

class Image
{
    private $code;
    private $fontList;

    private $options = [
        'image_width'     => 160,
        'image_height'    => 60,
        'fonts_directory' => __DIR__ . '/../resources/fonts',
        'fonts_shuffle'   => true,
        'fonts_size'      => 24,
        'fonts_tuning'    => [
            '3dlet.ttf' => [
                'size' => 32,
                'case' => self::FONT_CASE_LOWER
            ],

            'baby_blocks.ttf' => [
                'size' => 16,
                'case' => self::FONT_CASE_RANDOM
            ],

            'betsy_flanagan.ttf' => [
                'size' => 28,
                'case' => self::FONT_CASE_RANDOM
            ],

            'karmaticarcade.ttf' => [
                'size' => 20,
                'case' => self::FONT_CASE_RANDOM
            ],

            'tonight.ttf' => [
                'size' => 28,
                'case' => self::FONT_CASE_RANDOM
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
        $this->fontList = glob(realpath($this->options['fonts_directory']) . DIRECTORY_SEPARATOR . '*.ttf');
    }

    /**
     * @throws \Exception
     */
    public function __toString() : string
    {
        return $this->generate();
    }

    /**
     * @throws \Exception
     */
    public function generate() : string
    {
        $image = imagecreatetruecolor($this->options['image_width'], $this->options['image_height']);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        $this->drawTextOnImage($image);

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode(ob_get_clean());
    }

    /**
     * Drawing the text on the image
     *
     * @param       $image
     * @throws \Exception
     */
    private function drawTextOnImage(&$image) : void
    {
        $font = $this->fontList[mt_rand(0, count($this->fontList) - 1)];
        $code = str_split($this->code);
        $len = count($code);

        for ($i = 0; $i < $len; $i++) {
            if ($this->options['fonts_shuffle']) {
                $font = $this->fontList[mt_rand(0, count($this->fontList) - 1)];
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

    private function determineFontSize(string $fontName) : int
    {
        return isset($this->options['fonts_tuning'][$fontName])
            ? (int) $this->options['fonts_tuning'][$fontName]['size']
            : (int) $this->options['fonts_size'];
    }

    private function setLetterCase(string $string, string $fontName) : string
    {
        if (isset($this->options['fonts_tuning'][$fontName])) {
            switch ($this->options['fonts_tuning'][$fontName]['case']) {
                case 2:
                    return strtoupper($string);
                case 1:
                    return strtolower($string);
            }
        }

        return $string;
    }
}
