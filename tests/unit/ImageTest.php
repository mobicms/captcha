<?php

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use LogicException;
use Mobicms\Captcha\Configuration;
use Mobicms\Captcha\Image;
use Mobicms\Captcha\Options;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    private string $stub = __DIR__ . '/../stubs/';
    private Image $image;

    public function setUp(): void
    {
        $this->image = new Image('abcd');
    }

    /**
     * @throws \Exception
     */
    public function testCanGenerateDataImageString(): void
    {
        $image = $this->image->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @throws \Exception
     */
    public function testCanGenerateValidImage(): void
    {
        $this->writeImage($this->image->generate());
        $info = getimagesize($this->stub . 'test.png');
        $this->assertSame(190, $info[0]);
        $this->assertSame(80, $info[1]);
        $this->assertSame('image/png', $info['mime']);
    }

    public function testToString(): void
    {
        $image = (string) $this->image;
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @throws \Exception
     */
    public function testSetCustomFontsFolder(): void
    {
        $options = new Options();
        $options->setFontsFolder($this->stub);
        $image = (new Image('abcd', $options))->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    public function testFontsDoesNotExist(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The specified folder does not contain any fonts.');
        $options = new Options();
        $options->setFontsFolder(__DIR__);
        new Image('abcd', $options);
    }

    /**
     * @dataProvider customFontValues
     * @throws \Exception
     */
    public function testSetLetterCase(int $case): void
    {
        $options = new Options();
        $options
            ->setFontsFolder($this->stub)
            ->adjustFont('test.ttf', 32, $case);
        $captcha = new Image('abcd', $options);
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Auxiliary methods                                                          //
    ////////////////////////////////////////////////////////////////////////////////

    private function writeImage(string $image): void
    {
        $image = str_replace('data:image/png;base64,', '', $image);
        file_put_contents($this->stub . 'test.png', base64_decode($image));
    }

    /**
     * @psalm-return array<array<int>>
     */
    public function customFontValues(): array
    {
        return [
            'RANDOM' => [Configuration::FONT_CASE_RANDOM],
            'UPPER'  => [Configuration::FONT_CASE_UPPER],
            'LOWER'  => [Configuration::FONT_CASE_LOWER],
        ];
    }
}
