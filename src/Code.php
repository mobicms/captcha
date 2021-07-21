<?php

declare(strict_types=1);

namespace Mobicms\Captcha;

use InvalidArgumentException;

class Code
{
    /** @var array<array-key, int> */
    private array $length;
    private string $characterSet;

    public function __construct(
        int $lengthMin = null,
        int $lengthMax = null,
        string $characterSet = null
    ) {
        $this->length = $this->prepareLength($lengthMin, $lengthMax);
        $this->characterSet = $this->prepareCharSet($characterSet);
    }

    public function __toString(): string
    {
        return $this->generate();
    }

    public function generate(): string
    {
        $length = mt_rand($this->length[0], $this->length[1]);

        do {
            $code = substr(str_shuffle(str_repeat($this->characterSet, 3)), 0, $length);
        } while (preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $code));

        return $code;
    }

    /**
     * @return array<array-key, int>
     */
    private function prepareLength(?int $lengthMin, ?int $lengthMax): array
    {
        $min = $lengthMin ?? 3;
        $max = $lengthMax ?? 5;

        if ($min < 1 || $max > 20) {
            throw new InvalidArgumentException(
                'Allowed parameter value from 1 to 20'
            );
        }

        if ($max < $min) {
            throw new InvalidArgumentException(
                'The maximum length should not be less than the minimum'
            );
        }

        return [$min, $max];
    }

    private function prepareCharSet(?string $characterSet): string
    {
        $charset = $characterSet ?? '23456789ABCDEGHJKMNPQRSTUVXYZabcdeghjkmnpqrstuvxyz';

        if (empty($charset)) {
            throw new InvalidArgumentException(
                'Character set cannot be empty'
            );
        }

        return $charset;
    }
}
