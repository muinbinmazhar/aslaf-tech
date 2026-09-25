<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_form_renders_remember_and_password_visibility_controls(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('এই ডিভাইসে ৩৬৫ দিন লগইন রাখুন')
            ->assertSee('aria-label="পাসওয়ার্ড দেখান"', false)
            ->assertSee('type="password"', false)
            ->assertSee('name="remember" value="1" checked', false);
    }

    public function test_remembered_login_issues_a_cookie_for_365_days_and_restores_an_expired_session(): void
    {
        $this->freezeTime();
        $admin = User::factory()->create(['is_admin' => true, 'remember_token' => null]);

        $response = $this->post(route('admin.login.store'), [
            'email' => $admin->email, 'password' => 'password', 'remember' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $cookieName = Auth::guard()->getRecallerName();
        $cookie = $response->getCookie($cookieName);
        $this->assertNotNull($cookie);
        $this->assertSame(31536000, $cookie->getExpiresTime() - now()->getTimestamp());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertNotEmpty($admin->fresh()->remember_token);

        $this->travel(364)->days();
        $this->app['session']->flush();
        Auth::forgetGuards();
        $this->withCookie($cookieName, $cookie->getValue())
            ->get(route('admin.dashboard'))->assertOk();
        $this->assertAuthenticatedAs($admin);
        $this->assertTrue(Auth::viaRemember());
    }

    public function test_unchecked_remember_option_does_not_issue_a_persistent_login_cookie(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->post(route('admin.login.store'), [
            'email' => $admin->email, 'password' => 'password', 'remember' => '0',
        ]);

        $response->assertRedirect(route('admin.dashboard'))
            ->assertCookieMissing(Auth::guard()->getRecallerName());
        $this->assertAuthenticatedAs($admin);
    }

    public function test_logout_invalidates_the_remembered_login(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $login = $this->post(route('admin.login.store'), [
            'email' => $admin->email, 'password' => 'password', 'remember' => '1',
        ]);
        $cookieName = Auth::guard()->getRecallerName();
        $cookie = $login->getCookie($cookieName);

        $this->withCookie($cookieName, $cookie->getValue())->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'))->assertCookieExpired($cookieName);

        $this->assertGuest();
        $this->app['session']->flush();
        Auth::forgetGuards();
        $this->withCookie($cookieName, $cookie->getValue())
            ->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_wrong_password_does_not_create_a_remembered_login(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->post(route('admin.login.store'), [
            'email' => $admin->email, 'password' => 'incorrect', 'remember' => '1',
        ])->assertSessionHasErrors('email')->assertCookieMissing(Auth::guard()->getRecallerName());

        $this->assertGuest();
    }

    public function test_non_admin_cannot_keep_a_remembered_admin_login(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->post(route('admin.login.store'), [
            'email' => $user->email, 'password' => 'password', 'remember' => '1',
        ])->assertSessionHasErrors('email')->assertCookieMissing(Auth::guard()->getRecallerName());

        $this->assertGuest();
    }
}
