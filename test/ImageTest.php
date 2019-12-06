<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use Mobicms\Captcha\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    public function testCanCreateInstance(): Image
    {
        $captcha = new Image('abcd');
        $this->assertInstanceOf(Image::class, $captcha);
        return $captcha;
    }

    /**
     * @depends testCanCreateInstance
     * @param Image $captcha
     * @throws \Exception
     */
    public function testCanGenerateImage(Image $captcha): void
    {
        $image = $captcha->generate();
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }

    /**
     * @depends testCanCreateInstance
     * @param Image $captcha
     */
    public function testToString(Image $captcha): void
    {
        $image = (string) $captcha;
        $this->assertStringStartsWith('data:image/png;base64', $image);
    }
}
