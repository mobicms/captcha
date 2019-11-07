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

    private $options = [
        'image_width'     => 160,
        'image_height'    => 60,
        'fonts_directory' => __DIR__ . '/../resources/fonts',
        'fonts_shuffle'   => false,
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

            'granps.ttf' => [
                'size' => 26,
                'case' => self::FONT_CASE_UPPER
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
        $font = $this->chooseFont();
        $captcha = $this->prepareString($this->code, $font);

        $image = imagecreatetruecolor($this->options['image_width'], $this->options['image_height']);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, imagecolorallocatealpha($image, 0, 0, 0, 127));
        $this->drawText($image, $captcha, $font);

        ob_start();
        imagepng($image);
        imagedestroy($image);

        return 'data:image/png;base64,' . base64_encode(ob_get_clean());
    }

    /**
     * Drawing the text on the image
     *
     * @param       $image
     * @param array $captcha
     * @param       $font
     * @throws \Exception
     */
    private function drawText(&$image, array $captcha, $font) : void
    {
        $len = count($captcha);

        for ($i = 0; $i < $len; $i++) {
            $xPos = ($this->options['image_width'] - $this->options['fonts_size']) / $len * $i + ($this->options['fonts_size'] / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->options['image_height'] - (($this->options['image_height'] - $this->options['fonts_size']) / 2);
            $capcolor = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));
            $capangle = random_int(-25, 25);
            imagettftext($image, $this->options['fonts_size'], $capangle, $xPos, $yPos, $capcolor, $font, $captcha[$i]);
        }
    }

    /**
     * Choosing a random font from the list of available
     */
    private function chooseFont() : string
    {
        $fontsList = glob(realpath($this->options['fonts_directory']) . DIRECTORY_SEPARATOR . '*.ttf');

        return $fontsList[mt_rand(0, count($fontsList) - 1)];
    }

    /**
     * Set font size
     *
     * @param string $string
     * @param string $font
     * @return array
     */
    private function prepareString($string, $font) : array
    {
        $font = basename($font);

        if (isset($this->options['fonts_tuning'][$font])) {
            $args = $this->options['fonts_tuning'][$font];
            $this->options['fonts_size'] = $args['size'];
            $string = $this->setCase($string, $args);
        }

        return str_split($string);
    }

    /**
     * Set font case
     *
     * @param string $string
     * @param array  $args
     * @return string
     */
    private function setCase($string, array $args) : string
    {
        switch ($args['case']) {
            case 2:
                return strtoupper($string);
            case 1:
                return strtolower($string);
        }

        return $string;
    }
}
