<?php

declare(strict_types=1);

use Mobicms\Captcha\Code;

test('Can generate unique string', function () {
    $code = (string) new Code();
    $anotherCode = (string) new Code();

    expect(strlen($code))->toBeGreaterThanOrEqual(3);
    expect($code)->not->toEqual($anotherCode);
});

test('Can specify length', function () {
    $code = (string) new Code(2, 2);
    expect(strlen($code))->toEqual(2);

    $code = (string) new Code(5, 5);
    expect(strlen($code))->toEqual(5);
});

test('Ability to set your own character set', function () {
    $code = (string) new Code(3, 3, 'a');
    expect($code)->toEqual('aaa');

    $code = (string) new Code(3, 3, 'b');
    expect($code)->toEqual('bbb');
});

test(
    'Throwing an exception',
    function (int $lengthMin, int $lengthMax, string $characterSet) {
        new Code($lengthMin, $lengthMax, $characterSet);
    }
)
    ->with('invalid values')
    ->throws(InvalidArgumentException::class);

dataset('invalid values', function () {
    return [
        'length less than 1'                     => [0, 5, 'abcd'],
        'length greater than 20'                 => [3, 25, 'abcd'],
        'length minimum is greater than maximum' => [5, 3, 'abcd'],
        'Empty character set'                    => [3, 5, ''],
    ];
});
