<?php

use Zai\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide here will be executed before every test.
|
*/

// uses(TestCase::class)->in('Feature', 'Integration');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that a value meets
| certain conditions. The following functions allow you to do just that.
|
*/

expect()->extend('toBeValidJwt', function () {
    return $this->toBeString()
        ->and(explode('.', $this->value))->toHaveCount(3);
});
