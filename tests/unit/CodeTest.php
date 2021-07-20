<?php

declare(strict_types=1);

namespace MobicmsTest\Captcha;

use InvalidArgumentException;
use Mobicms\Captcha\Code;
use PHPUnit\Framework\TestCase;

class CodeTest extends TestCase
{
    public function testCanGenerateRandomCode(): void
    {
        $code = (new Code())->generate();
        $this->assertGreaterThanOrEqual(3, strlen($code));
    }

    public function testToString(): void
    {
        $code = (string) new Code();
        $this->assertGreaterThanOrEqual(3, strlen($code));
    }

    public function testCanSpecifyLength(): void
    {
        $code = (string) new Code(2, 2);
        $this->assertEquals(2, strlen($code));
        $code = (string) new Code(5, 5);
        $this->assertEquals(5, strlen($code));
    }

    public function testCanSpecifyLetters(): void
    {
        $code = (string) new Code(3, 3, 'aaaaa');
        $this->assertEquals('aaa', $code);
        $code = (string) new Code(3, 3, 'bb');
        $this->assertEquals('bbb', $code);
    }

    /**
     * @dataProvider invalidValues
     */
    public function testInvalidParameterValues(
        int $lengthMin,
        int $lengthMax,
        string $characterSet
    ): void {
        $this->expectException(InvalidArgumentException::class);
        new Code($lengthMin, $lengthMax, $characterSet);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Auxiliary methods                                                          //
    ////////////////////////////////////////////////////////////////////////////////

    /**
     * @return array[]
     */
    public function invalidValues(): array
    {
        return [
            'Minimum length value less than 1'        => [0, 5, 'abcd'],
            'Maximum length value is greater than 20' => [3, 25, 'abcd'],
            'Minimum length is greater than maximum'  => [5, 3, 'abcd'],
            'Empty character set'                     => [3, 5, ''],
        ];
    }
}
