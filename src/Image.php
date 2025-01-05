<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use GdImage;
use LogicException;

class Image
{
    public const FONT_CASE_UPPER = 2;
    public const FONT_CASE_LOWER = 1;

    public int $imageWidth = 190;
    public int $imageHeight = 80;
    public string $fontFolder = __DIR__ . '/../fonts';
    public int $fontSize = 26;
    public bool $fontMix = true;

    /**
     * Configuring individual font options
     *
     * @var array<string, array<string, int>>
     */
    public array $fontsTune = [
        '3dlet.ttf' => [
            'size' => 38,
            'case' => self::FONT_CASE_LOWER,
        ],

        'baby_blocks.ttf' => [
            'size' => 16,
        ],

        'betsy_flanagan.ttf' => [
            'size' => 30,
        ],

        'karmaticarcade.ttf' => [
            'size' => 20,
        ],

        'tonight.ttf' => [
            'size' => 28,
        ],
    ];

    /** @var array<string> */
    private array $fontList;

    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @throws \Exception
     */
    public function generate(): string
    {
        $this->fontList = $this->prepareFontsList();

        ob_start();
        $image = imagecreatetruecolor($this->imageWidth, $this->imageHeight);

        if ($image !== false) {
            $color = imagecolorallocatealpha($image, 0, 0, 0, 127);

            if ($color !== false) {
                imagesavealpha($image, true);
                imagefill($image, 0, 0, $color);
                $image = $this->drawTextOnImage($image);
                imagepng($image);
                imagedestroy($image);
            }
        }

        $out = ob_get_clean();
        return 'data:image/png;base64,' . base64_encode((string) $out);
    }

    /**
     * @throws \Exception
     */
    private function drawTextOnImage(GdImage $image): GdImage
    {
        $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
        $symbols = str_split($this->code);
        $len = count($symbols);

        foreach ($symbols as $i => $iValue) {
            if ($this->fontMix) {
                $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
            }

            $fontName = basename($font);
            $letter = $this->setLetterCase($iValue, $fontName);
            $fontSize = $this->fontsTune[$fontName]['size'] ?? $this->fontSize;
            $xPos = ($this->imageWidth - $fontSize) / $len * $i + ($fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->imageHeight - (($this->imageHeight - $fontSize) / 2);
            $angle = random_int(-25, 25);
            $color = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));

            if ($color !== false) {
                imagettftext($image, $fontSize, $angle, $xPos, (int) $yPos, $color, $font, $letter);
            }
        }

        return $image;
    }

    private function setLetterCase(string $string, string $fontName): string
    {
        $case = $this->fontsTune[$fontName]['case'] ?? 0;
        return match ($case) {
            self::FONT_CASE_UPPER => strtoupper($string),
            self::FONT_CASE_LOWER => strtolower($string),
            default => $string,
        };
    }

    /**
     * @return array<string>
     */
    private function prepareFontsList(): array
    {
        $list = glob($this->fontFolder . DIRECTORY_SEPARATOR . '*.ttf');

        if ([] === $list || false === $list) {
            throw new LogicException('The specified folder does not contain any fonts.');
        }

        return $list;
    }
}
