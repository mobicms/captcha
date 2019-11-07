<?php

declare(strict_types=1);

/**
 * @copyright   Oleg Kasyanov <dev@mobicms.net>
 * @license     https://opensource.org/licenses/MIT MIT (see the LICENSE file)
 * @link        https://github.com/batumibiz/captcha
 */

namespace Batumibiz\Captcha;

/**
 * @deprecated
 */
class Captcha
{
    public function generateCode() : string
    {
        return (string) new Code;
    }

    public function generateImage(string $code) : string
    {
        return (string) new Image($code);
    }
}
