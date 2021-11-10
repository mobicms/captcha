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

    private Configuration $config;

    public function __construct(string|Stringable $code, Configuration $config = null)
    {
        $this->code = (string) $code;
        $this->config = $config ?? new Configuration();
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
        $image = imagecreatetruecolor($this->config->getImageWidth(), $this->config->getImageHeight());

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
        $code = str_split($this->code);
        $len = count(/** @scrutinizer ignore-type */ $code);

        foreach ($code as $i => $iValue) {
            if ($this->config->getFontShuffle()) {
                $font = $this->fontList[random_int(0, count($this->fontList) - 1)];
            }

            $fontName = basename($font);
            $letter = $this->setLetterCase($iValue, $fontName);
            $fontSize = $this->determineFontSize($fontName);
            $xPos = ($this->config->getImageWidth() - $fontSize) / $len * $i + ($fontSize / 2);
            $xPos = random_int((int) $xPos, (int) $xPos + 5);
            $yPos = $this->config->getImageHeight() - (($this->config->getImageHeight() - $fontSize) / 2);
            $capangle = random_int(-25, 25);
            $capcolor = imagecolorallocate($image, random_int(0, 150), random_int(0, 150), random_int(0, 150));

            if ($capcolor !== false) {
                imagettftext($image, $fontSize, $capangle, $xPos, (int) $yPos, $capcolor, $font, $letter);
            }
        }

        return $image;
    }

    private function determineFontSize(string $fontName): int
    {
        $config = $this->config->getFontsConfiguration();
        return isset($config[$fontName])
            ? $config[$fontName]['size']
            : $this->config->getDefaultFontSize();
    }

    private function setLetterCase(string $string, string $fontName): string
    {
        $config = $this->config->getFontsConfiguration();

        if (isset($config[$fontName])) {
            switch ($config[$fontName]['case']) {
                case Configuration::FONT_CASE_UPPER:
                    return strtoupper($string);
                case Configuration::FONT_CASE_LOWER:
                    return strtolower($string);
            }
        }

        return $string;
    }

    /**
     * @return array<string>
     */
    private function prepareFontsList(): array
    {
        $list = glob(realpath($this->config->getFontsFolder()) . DIRECTORY_SEPARATOR . '*.ttf');

        if ([] === $list || false === $list) {
            throw new LogicException('The specified folder does not contain any fonts.');
        }

        return $list;
    }
}
