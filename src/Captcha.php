<?php

declare(strict_types=1);

/**
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE.md file)
 * @link        https://github.com/batumibiz/captcha
 */

namespace Batumibiz\Captcha;

class Captcha
{
    /**
     * @var int Image Width
     */
    public $width = 160;

    /**
     * @var int Image Height
     */
    public $height = 50;

    /**
     * @var int Default font size
     */
    public $fontSize = 24;

    /**
     * @var array Individual sizes of fonts (if not present, use default)
     */
    public $customFonts = [
        '3dlet.ttf'          => ['size' => 32, 'case' => 1],
        'baby_blocks.ttf'    => ['size' => 16, 'case' => 0],
        'betsy_flanagan.ttf' => ['size' => 28, 'case' => 0],
        'granps.ttf'         => ['size' => 26, 'case' => 2],
        'karmaticarcade.ttf' => ['size' => 20, 'case' => 0],
        'tonight.ttf'        => ['size' => 28, 'case' => 0],
    ];

    /**
     * @var int The minimum length of Captcha
     */
    public $lenghtMin = 3;

    /**
     * @var int The maximum length of Captcha
     */
    public $lenghtMax = 5;

    /**
     * @var string Symbols used in Captcha
     */
    public $letters = '23456789ABCDEGHKMNPQSUVXYZabcdeghkmnpqsuvxyz';

    /**
     * Captcha code generation
     *
     * @throws \Exception
     * @return string
     */
    public function generateCode() : string
    {
        $lenght = random_int($this->lenghtMin, $this->lenghtMax);

        do {
            $code = substr(str_shuffle(str_repeat($this->letters, 3)), 0, $lenght);
        } while (preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $code));

        return $code;
    }

    /**
     * Captcha image generation
     *
     * @param $string
     * @throws \Exception
     * @return string
     */
    public function generateImage($string) : string
    {
        $font = $this->chooseFont();
        $captcha = $this->prepareString($string, $font);

        $image = imagecreatetruecolor($this->width, $this->height);
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
            $xPos = ($this->width - $this->fontSize) / $len * $i + ($this->fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->height - (($this->height - $this->fontSize) / 2);
            $capcolor = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));
            $capangle = random_int(-25, 25);
            imagettftext($image, $this->fontSize, $capangle, $xPos, $yPos, $capcolor, $font, $captcha[$i]);
        }
    }

    /**
     * Choosing a random font from the list of available
     *
     * @throws \Exception
     * @return string
     */
    private function chooseFont() : string
    {
        $dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR;
        $fontsList = glob($dir . '*.ttf');
        $font = basename($fontsList[random_int(0, count($fontsList) - 1)]);

        return $dir . $font;
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

        if (isset($this->customFonts[$font])) {
            $args = $this->customFonts[$font];
            $this->fontSize = $args['size'];
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
