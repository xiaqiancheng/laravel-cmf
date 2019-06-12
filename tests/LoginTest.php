<?php

namespace XADMIN\LaravelCmf\Tests;

use Illuminate\Support\Facades\Auth;

class LoginTest extends TestCase
{
    public function testSuccessfulLoginWithDefaultCredentials()
    {
        $this->visit(route('laravel-cmf.login'))
             ->type('admin@admin.com', 'email')
             ->type('password', 'password')
             ->press(__('laravel-cmf::generic.login'))
             ->seePageIs(route('laravel-cmf.dashboard'));
    }

    public function testShowAnErrorMessageWhenITryToLoginWithWrongCredentials()
    {
        session()->setPreviousUrl(route('laravel-cmf.login'));

        $this->visit(route('laravel-cmf.login'))
             ->type('john@Doe.com', 'email')
             ->type('pass', 'password')
             ->press(__('laravel-cmf::generic.login'))
             ->seePageIs(route('laravel-cmf.login'))
             ->see(__('auth.failed'))
             ->seeInField('email', 'john@Doe.com');
    }

    public function testRedirectIfLoggedIn()
    {
        Auth::loginUsingId(1);

        $this->visit(route('laravel-cmf.login'))
             ->seePageIs(route('laravel-cmf.dashboard'));
    }

    public function testRedirectIfNotLoggedIn()
    {
        $this->visit(route('laravel-cmf.profile'))
             ->seePageIs(route('laravel-cmf.login'));
    }

    public function testCanLogout()
    {
        Auth::loginUsingId(1);

        $this->visit(route('laravel-cmf.dashboard'))
             ->press(__('laravel-cmf::generic.logout'))
             ->seePageIs(route('laravel-cmf.login'));
    }

    public function testGetsLockedOutAfterFiveAttempts()
    {
        session()->setPreviousUrl(route('laravel-cmf.login'));

        for ($i = 0; $i <= 5; $i++) {
            $t = $this->visit(route('laravel-cmf.login'))
                 ->type('john@Doe.com', 'email')
                 ->type('pass', 'password')
                 ->press(__('laravel-cmf::generic.login'));
        }

        $t->see(__('auth.throttle', ['seconds' => 60]));
    }
}
