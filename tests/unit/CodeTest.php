<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/mobicms/captcha
 */

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use Exception;
use Mobicms\Captcha\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCanGenerateRandomCode(): void
    {
        $code = (new Code())->generate();
        $this->assertIsString($code);
        $this->assertGreaterThanOrEqual(3, strlen($code));
    }

    public function testToString(): void
    {
        $code = (string) new Code();
        $this->assertIsString($code);
        $this->assertGreaterThanOrEqual(3, strlen($code));
    }

    public function testCanSpecifyLength(): void
    {
        $code = (string) new Code(2, 2);
        $this->assertSame(2, strlen($code));
        $code = (string) new Code(5, 5);
        $this->assertSame(5, strlen($code));
    }

    public function testCanSpecifyLetters(): void
    {
        $code = (string) new Code(3, 3, 'aaaaa');
        $this->assertSame('aaa', $code);
        $code = (string) new Code(3, 3, 'bb');
        $this->assertSame('bbb', $code);
    }
}
