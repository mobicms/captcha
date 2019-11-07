<?php

declare(strict_types=1);

namespace BatumibizTest\Captcha;

use Batumibiz\Captcha\Captcha;
use PHPUnit\Framework\TestCase;

class CaptchaTest extends TestCase
{
    private $captcha;

    public function setUp() : void
    {
        $this->captcha = new Captcha;
    }

    public function testGenerateCode()
    {
        $code = $this->captcha->generateCode();
        $this->assertIsString($code);
        $len = strlen($code);
        $this->assertGreaterThanOrEqual(3, $len);
    }

    public function testGenerateImage()
    {
        $image = $this->captcha->generateImage('test-image');
        $this->assertStringStartsWith('data:image/png;base64,', $image);
    }
}
