<?php

declare(strict_types=1);

namespace Mobicms\Captcha\Exception;

use InvalidArgumentException;

final class ConfigException extends InvalidArgumentException implements CaptchaException
{
}
