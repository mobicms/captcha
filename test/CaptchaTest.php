<?php

namespace MobicmsTest\Captcha;

use Mobicms\Captcha\Captcha;
use PHPUnit\Framework\TestCase;

class CaptchaTest extends TestCase
{
    public function testGenerateCode()
    {
        $captcha = new Captcha;
        $code = $captcha->generateCode();
        $len = strlen($code);
        $this->assertIsString($code);
        $this->assertGreaterThanOrEqual($captcha->lenghtMin, $len);
    }

    public function testGenerateImage()
    {
        $captcha = new Captcha;
        $image = $captcha->generateImage('test-image');
        $this->assertIsString($image);
        $this->assertStringStartsWith('data:image/png;base64,', $image);
    }
}
