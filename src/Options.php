<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use InvalidArgumentException;
use Stringable;

use function pathinfo;
use function is_dir;

class Options extends Configuration
{
    public function setImageHeight(int $height): self
    {
        if ($height < 10) {
            throw new InvalidArgumentException('Image size cannot be less than 20x10px');
        }

        $this->imageHeight = $height;
        return $this;
    }

    public function setImageWidth(int $width): self
    {
        if ($width < 20) {
            throw new InvalidArgumentException('Image size cannot be less than 20x10px');
        }

        $this->imageWidth = $width;
        return $this;
    }

    public function setFontsFolder(string|Stringable $folder): self
    {
        $this->fontsFolder = (string) $folder;

        if (! is_dir($this->fontsFolder)) {
            throw new InvalidArgumentException('The specified folder does not exist.');
        }

        return $this;
    }

    public function setFontShuffle(bool $shuffle): self
    {
        $this->fontShuffle = $shuffle;
        return $this;
    }

    public function setDefaultFontSize(int $size): self
    {
        if ($size < 5) {
            throw new InvalidArgumentException('You specified the wrong font size.');
        }

        $this->defaultFontSize = $size;
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

        $this->fontsConfiguration[$fontName] = [
            'size' => $size,
            'case' => $case,
        ];
        return $this;
    }
}
