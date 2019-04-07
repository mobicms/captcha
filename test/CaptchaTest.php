<?php

namespace MobicmsTest\Captcha;

use Mobicms\Captcha\Captcha;
use PHPUnit\Framework\TestCase;

class CaptchaTest extends TestCase
{
    private $captcha;

    public function setUp() : void
    {
        $this->captcha = new Captcha;
        $this->captcha->customFonts = [
            '3dlet.ttf'                 => ['size' => 32, 'case' => 0],
            'baby_blocks.ttf'           => ['size' => 16, 'case' => 0],
            'ball.ttf'                  => ['size' => 16, 'case' => 0],
            'betsy_flanagan.ttf'        => ['size' => 28, 'case' => 0],
            'bloktilt.ttf'              => ['size' => 26, 'case' => 0],
            'cangoods.ttf'              => ['size' => 26, 'case' => 0],
            'elevator_buttons.ttf'      => ['size' => 26, 'case' => 0],
            'granps.ttf'                => ['size' => 26, 'case' => 0],
            'karmaticarcade.ttf'        => ['size' => 20, 'case' => 0],
            'platinumhubcapsspoked.ttf' => ['size' => 28, 'case' => 0],
            'tonight.ttf'               => ['size' => 28, 'case' => 0],
        ];
    }

    public function testGenerateCode()
    {
        $code = $this->captcha->generateCode();
        $len = strlen($code);
        $this->assertGreaterThanOrEqual($this->captcha->lenghtMin, $len);
    }

    public function testGenerateImage()
    {
        $image = $this->captcha->generateImage('test-image');
        $this->assertStringStartsWith('data:image/png;base64,', $image);
    }

    public function testGenerateImageWithLowercaseFont()
    {
        $this->captcha->customFonts = [
            '3dlet.ttf'                 => ['size' => 32, 'case' => 1],
            'baby_blocks.ttf'           => ['size' => 16, 'case' => 1],
            'ball.ttf'                  => ['size' => 16, 'case' => 1],
            'betsy_flanagan.ttf'        => ['size' => 28, 'case' => 1],
            'bloktilt.ttf'              => ['size' => 26, 'case' => 1],
            'cangoods.ttf'              => ['size' => 26, 'case' => 1],
            'elevator_buttons.ttf'      => ['size' => 26, 'case' => 1],
            'granps.ttf'                => ['size' => 26, 'case' => 1],
            'karmaticarcade.ttf'        => ['size' => 20, 'case' => 1],
            'platinumhubcapsspoked.ttf' => ['size' => 28, 'case' => 1],
            'tonight.ttf'               => ['size' => 28, 'case' => 1],
        ];
        $image = $this->captcha->generateImage('test-image');
        $this->assertStringStartsWith('data:image/png;base64,', $image);
    }

    public function testGenerateImageWithUppercaseFont()
    {
        $this->captcha->customFonts = [
            '3dlet.ttf'                 => ['size' => 32, 'case' => 2],
            'baby_blocks.ttf'           => ['size' => 16, 'case' => 2],
            'ball.ttf'                  => ['size' => 16, 'case' => 2],
            'betsy_flanagan.ttf'        => ['size' => 28, 'case' => 2],
            'bloktilt.ttf'              => ['size' => 26, 'case' => 2],
            'cangoods.ttf'              => ['size' => 26, 'case' => 2],
            'elevator_buttons.ttf'      => ['size' => 26, 'case' => 2],
            'granps.ttf'                => ['size' => 26, 'case' => 2],
            'karmaticarcade.ttf'        => ['size' => 20, 'case' => 2],
            'platinumhubcapsspoked.ttf' => ['size' => 28, 'case' => 2],
            'tonight.ttf'               => ['size' => 28, 'case' => 2],
        ];
        $image = $this->captcha->generateImage('test-image');
        $this->assertStringStartsWith('data:image/png;base64,', $image);
    }
}
