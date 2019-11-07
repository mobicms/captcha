<?php

declare(strict_types=1);

/**
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

namespace Batumibiz\Captcha;

class Code
{
    private $lengthMin;
    private $lengthMax;
    private $letters;

    /**
     * @param int    $lengthMin The minimum length of CAPTCHA code
     * @param int    $lengthMax The maximum length of CAPTCHA code
     * @param string $letters   The set of letters used in CAPTCHA
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

    public function generate() : string
    {
        $lenght = mt_rand($this->lengthMin, $this->lengthMax);

        do {
            $code = substr(str_shuffle(str_repeat($this->letters, 3)), 0, $lenght);
        } while (preg_match('/cp|cb|ck|c6|c9|rn|rm|mm|co|do|cl|db|qp|qb|dp|ww/', $code));

        return $code;
    }

    public function __toString() : string
    {
        return $this->generate();
    }
}
