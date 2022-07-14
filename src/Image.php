<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use GdImage;
use LogicException;
use Stringable;

class Image implements Stringable
{
    /** @var array<string> */
    private array $fontList;

    private string $code;

    private ImageOptions $imageOptions;

    public function __construct(
        string|Stringable $code,
        ImageOptions $imageOptions = null
    ) {
        $this->code = (string) $code;
        $this->imageOptions = $imageOptions ?? new ImageOptions();
        $this->fontList = $this->prepareFontsList();
    }

    /**
     * @throws \Exception
     */
    public function __toString(): string
    {
        return $this->generate();
    }

    /**
     * @throws \Exception
     */
    public function generate(): string
    {
        ob_start();
        $image = imagecreatetruecolor($this->imageOptions->getWidth(), $this->imageOptions->getHeight());

        if ($image !== false) {
            $color = imagecolorallocatealpha($image, 0, 0, 0, 127);

            if ($color !== false) {
                imagesavealpha($image, true);
                imagefill($image, 0, 0, $color);
                $image = $this->drawTextOnImage(/** @scrutinizer ignore-type */ $image);
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
        $len = count(/** @scrutinizer ignore-type */ $symbols);

        foreach ($symbols as $i => $iValue) {
            if ($this->imageOptions->getFontShuffle()) {
                $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
            }

            $fontName = basename($font);
            $letter = $this->setLetterCase($iValue, $fontName);
            $fontSize = $this->imageOptions->getFontSize($fontName);
            $xPos = ($this->imageOptions->getWidth() - $fontSize) / $len * $i + ($fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->imageOptions->getHeight() - (($this->imageOptions->getHeight() - $fontSize) / 2);
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
        return match ($this->imageOptions->getFontCase($fontName)) {
            ImageOptions::FONT_CASE_UPPER => strtoupper($string),
            ImageOptions::FONT_CASE_LOWER => strtolower($string),
            default => $string,
        };
    }

    /**
     * @return array<string>
     */
    private function prepareFontsList(): array
    {
        $list = glob(realpath($this->imageOptions->getFontsFolder()) . DIRECTORY_SEPARATOR . '*.ttf');

        if ([] === $list || false === $list) {
            throw new LogicException('The specified folder does not contain any fonts.');
        }

        return $list;
    }
}
