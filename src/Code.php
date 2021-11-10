<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use InvalidArgumentException;
use Stringable;

class Code implements Stringable
{
    private int $lengthMin;

    private int $lengthMax;

    private string $characterSet;

    public function __construct(
        int $lengthMin = 3,
        int $lengthMax = 5,
        string|Stringable $characterSet = '23456789ABCDEGHJKMNPQRSTUVXYZabcdeghjkmnpqrstuvxyz'
    ) {
        $this->lengthMin = $lengthMin;
        $this->lengthMax = $lengthMax;
        $this->characterSet = (string) $characterSet;

        if ($lengthMin < 1 || $lengthMax > 20) {
            throw new InvalidArgumentException(
                'Allowed length parameter value from 1 to 20'
            );
        }

        if ($lengthMax < $lengthMin) {
            throw new InvalidArgumentException(
                'The maximum length should not be less than the minimum'
            );
        }

        if (empty($this->characterSet)) {
            throw new InvalidArgumentException(
                'Character set cannot be empty'
            );
        }
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function generate(): string
    {
        $length = mt_rand($this->lengthMin, $this->lengthMax);

        do {
            $code = substr(str_shuffle(str_repeat($this->characterSet, 3)), 0, $length);
        } while (preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $code));

        return $code;
    }
}
