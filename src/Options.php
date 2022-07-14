<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use InvalidArgumentException;
use Stringable;

use function pathinfo;
use function is_dir;

class Options
{
    public const FONT_CASE_UPPER = 2;
    public const FONT_CASE_LOWER = 1;

    protected int $imageHeight = 80;
    protected int $imageWidth = 190;
    protected string $fontsFolder = __DIR__ . '/../resources/fonts';
    protected bool $fontShuffle = true;
    protected int $defaultFontSize = 26;

    /**
     * @var array<string, array<int>>
     */
    protected array $fontsConfiguration = [
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

    public function setImageHeight(int $height): self
    {
        if ($height < 10) {
            throw new InvalidArgumentException('Image size cannot be less than 20x10px');
        }

        $this->imageHeight = $height;
        return $this;
    }

    public function getImageHeight(): int
    {
        return $this->imageHeight;
    }

    public function setImageWidth(int $width): self
    {
        if ($width < 20) {
            throw new InvalidArgumentException('Image size cannot be less than 20x10px');
        }

        $this->imageWidth = $width;
        return $this;
    }

    public function getImageWidth(): int
    {
        return $this->imageWidth;
    }

    public function setFontsFolder(string|Stringable $folder): self
    {
        $this->fontsFolder = (string) $folder;

        if (! is_dir($this->fontsFolder)) {
            throw new InvalidArgumentException('The specified folder does not exist.');
        }

        return $this;
    }

    public function getFontsFolder(): string
    {
        return realpath($this->fontsFolder);
    }

    public function setFontShuffle(bool $shuffle): self
    {
        $this->fontShuffle = $shuffle;
        return $this;
    }

    public function getFontShuffle(): bool
    {
        return $this->fontShuffle;
    }

    public function setDefaultFontSize(int $size): self
    {
        if ($size < 5) {
            throw new InvalidArgumentException('You specified the wrong font size.');
        }

        $this->defaultFontSize = $size;
        return $this;
    }

    public function getFontSize(string $font = ''): int
    {
        return $this->fontsConfiguration[$font]['size'] ?? $this->defaultFontSize;
    }

    public function getFontCase(string $font): int
    {
        return $this->fontsConfiguration[$font]['case'] ?? 0;
    }

    public function adjustFont(
        string $fontName,
        int $size,
        int $case = 0
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
