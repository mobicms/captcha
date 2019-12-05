<?php

/**
 * This file is part of mobicms/captcha library
 *
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

declare(strict_types=1);

namespace Mobicms\Captcha;

use Exception;

class Code
{
    private $lengthMin;

    private $lengthMax;

    private $letters;

    /**
     * @param int $lengthMin The minimum length of CAPTCHA code
     * @param int $lengthMax The maximum length of CAPTCHA code
     * @param string $letters The set of letters used in CAPTCHA
     */
    public function __construct(
        int $lengthMin = 3,
        int $lengthMax = 5,
        string $letters = '23456789ABCDEGHKMNPQSUVXYZabcdeghkmnpqsuvxyz'
    ) {
        $this->lengthMin = $lengthMin;
        $this->lengthMax = $lengthMax;
        $this->letters = $letters;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function __toString(): string
    {
        return $this->generate();
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generate(): string
    {
        $length = random_int($this->lengthMin, $this->lengthMax);

        do {
            $code = substr(str_shuffle(str_repeat($this->letters, 3)), 0, $length);
        } while (preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $code));

        return $code;
    }
}
