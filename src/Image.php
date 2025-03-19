<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use GdImage;
use LogicException;

use function base64_encode;
use function basename;
use function count;
use function imagecolorallocate;
use function imagecolorallocatealpha;
use function imagecreatetruecolor;
use function imagedestroy;
use function imagefill;
use function imagepng;
use function imagesavealpha;
use function imagettftext;
use function ob_get_clean;
use function ob_start;
use function preg_match;
use function random_int;
use function str_repeat;
use function str_shuffle;
use function strtolower;
use function strtoupper;
use function substr;

/**
 * @psalm-api
 */
final class Image
{
    /** @var array<string> */
    private array $fontList = [];
    private string $code;

    public const FONT_CASE_UPPER = 2;
    public const FONT_CASE_LOWER = 1;

    ////////////////////////////////////////////////////////////
    // Image options                                          //
    ////////////////////////////////////////////////////////////
    public int $imageWidth = 190;
    public int $imageHeight = 90;
    public int $defaultFontSize = 30;
    public bool $fontMix = true;

    /** @var array<string> */
    public array $fontFolders = [__DIR__ . '/../fonts'];

    /** @var array<string, array<string, int>> */
    public array $fontsTune = [
        '3dlet.ttf' => [
            'size' => 16,
            'case' => self::FONT_CASE_LOWER,
        ],
        'baby_blocks.ttf' => [
            'size' => -8,
        ],
        'karmaticarcade.ttf' => [
            'size' => -4,
        ],
        'betsy_flanagan.ttf' => [
            'size' => 4,
        ],
    ];

    ////////////////////////////////////////////////////////////
    // Code options                                           //
    ////////////////////////////////////////////////////////////
    public int $lengthMin = 4;
    public int $lengthMax = 5;
    public string $characterSet = '23456789ABCDEGHJKMNPQRSTUVXYZabcdeghjkmnpqrstuvxyz';
    public string $excludedCombinationsPattern = 'rn|rm|mm|ww';

    public function __construct(string $code = '')
    {
        $this->code = $code;
    }

    /**
     * Generates a random code if none is provided.
     *
     * @throws \Random\RandomException
     */
    public function getCode(): string
    {
        if ($this->code === '') {
            $length = random_int($this->lengthMin, $this->lengthMax);

            do {
                $this->code = substr(str_shuffle(str_repeat($this->characterSet, 3)), 0, $length);
            } while (preg_match('/' . $this->excludedCombinationsPattern . '/', $this->code));
        }

        return $this->code;
    }

    /**
     * Creates a PNG image with a transparent background.
     *
     * @throws \Exception
     */
    public function getImage(): string
    {
        $this->fontList = $this->getFontsList();

        ob_start();
        $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);

        if ($image !== false) {
            $color = imagecolorallocatealpha($image, 0, 0, 0, 127);

            if ($color !== false) {
                imagesavealpha($image, true);
                imagefill($image, 0, 0, $color);
                $image = $this->drawText($image);
                imagepng($image);
                imagedestroy($image);
            }
        }

        return 'data:image/png;base64,' . base64_encode((string) ob_get_clean());
    }

    /**
     * Draws the captcha text on the image using random font, size, color, and angle.
     *
     * @throws \Random\RandomException
     */
    private function drawText(GdImage $image): GdImage
    {
        $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
        $symbols = str_split($this->getCode());
        $len = count($symbols);

        foreach ($symbols as $key => $symbol) {
            if ($this->fontMix) {
                $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
            }

            $fontName = basename($font);
            $letter = $this->setLetterCase($symbol, $fontName);
            $fontSize = $this->getFontSize($fontName);
            $xPos = intval(($this->imageWidth - $fontSize) / $len) * $key + intval($fontSize / 2);
            $xPos = random_int($xPos, $xPos + 5);
            $yPos = $this->imageHeight - intval(($this->imageHeight - $fontSize) / 2);
            $angle = random_int(-25, 25);
            $color = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));

            if ($color !== false) {
                imagettftext($image, $fontSize, $angle, $xPos, (int) $yPos, $color, $font, $letter);
            }
        }

        return $image;
    }

    /**
     * Adjusts the case of a letter based on font-specific settings.
     */
    private function setLetterCase(string $string, string $fontName): string
    {
        return match ($this->fontsTune[$fontName]['case'] ?? 0) {
            self::FONT_CASE_UPPER => strtoupper($string),
            self::FONT_CASE_LOWER => strtolower($string),
            default => $string,
        };
    }

    /**
     * Retrieves the list of font files from the specified directories.
     *
     * @return array<string>
     */
    private function getFontsList(): array
    {
        $fonts = [];

        foreach ($this->fontFolders as $folder) {
            $list = glob($folder . DIRECTORY_SEPARATOR . '*.ttf');

            if ([] === $list || false === $list) {
                throw new LogicException('The specified folder "' . $folder . '" does not contain any fonts.');
            }

            $fonts = array_merge($fonts, $list);
        }

        return $fonts;
    }

    /**
     * Calculates the font size based on default size and font-specific tuning.
     */
    private function getFontSize(string $fontName): int
    {
        return isset($this->fontsTune[$fontName]['size'])
            ? $this->defaultFontSize + $this->fontsTune[$fontName]['size']
            : $this->defaultFontSize;
    }
}
