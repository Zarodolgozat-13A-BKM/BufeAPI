<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

test('login validates required username and password', function () {
    $request = Request::create('/api/account/login', 'POST', []);

    expect(fn() => app(AuthController::class)->login($request))
        ->toThrow(ValidationException::class);
});

test('login returns unauthorized for invalid credentials', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->with([
            'samaccountname' => 'wrong-user',
            'password' => 'wrong-password',
        ])
        ->andReturn(false);

    $request = Request::create('/api/account/login', 'POST', [
        'username' => 'wrong-user',
        'password' => 'wrong-password',
    ]);

    $response = app(AuthController::class)->login($request);

    expect($response->getStatusCode())->toBe(401);
    expect($response->getData(true))->toBe([
        'message' => 'Érvénytelen bejelentkezési adatok',
    ]);
});

test('login returns access token for valid credentials', function () {
    $user = mock(User::class);

    $user->shouldReceive('createToken')
        ->once()
        ->with('auth_token')
        ->andReturn((object) [
            'plainTextToken' => 'token-123',
        ]);

    Auth::shouldReceive('attempt')
        ->once()
        ->with([
            'samaccountname' => 'john',
            'password' => 'password123',
        ])
        ->andReturn(true);

    Auth::shouldReceive('user')
        ->once()
        ->andReturn($user);

    $request = Request::create('/api/account/login', 'POST', [
        'username' => 'john',
        'password' => 'password123',
    ]);

    $response = app(AuthController::class)->login($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe(['access_token' => 'token-123']);
});

test('login assigns admin role for teszt user', function () {
    $user = mock(User::class);

    $user->shouldReceive('assignRole')
        ->once()
        ->with('admin');

    $user->shouldReceive('createToken')
        ->once()
        ->with('auth_token')
        ->andReturn((object) [
            'plainTextToken' => 'token-123',
        ]);

    Auth::shouldReceive('attempt')
        ->once()
        ->with([
            'samaccountname' => 'teszt',
            'password' => 'password123',
        ])
        ->andReturn(true);

    Auth::shouldReceive('user')
        ->once()
        ->andReturn($user);

    $request = Request::create('/api/account/login', 'POST', [
        'username' => 'teszt',
        'password' => 'password123',
    ]);

    $response = app(AuthController::class)->login($request);

    expect($response->getStatusCode())->toBe(200);
});

test('logout revokes current access token', function () {
    $token = mock();
    $token->shouldReceive('delete')->once();

    $user = mock(User::class);
    $user->shouldReceive('currentAccessToken')->once()->andReturn($token);

    $request = Request::create('/api/account/logout', 'POST');
    $request->setUserResolver(fn() => $user);

    $response = app(AuthController::class)->logout($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe(['message' => 'Sikeresen kijelentkeztél!']);
});

test('me returns authenticated user basic fields', function () {
    $user = new User([
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'admin',
    ]);

    $request = Request::create('/api/account/me', 'GET');
    $request->setUserResolver(fn() => $user);

    $response = app(AuthController::class)->me($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe([
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'role' => 'admin',
    ]);
});

test('details returns user resource payload', function () {
    $user = new User([
        'full_name' => 'Jane Doe',
    ]);
    $user->setRelation('orders', collect());

    $request = Request::create('/api/account/details', 'GET');
    $request->setUserResolver(fn() => $user);

    $response = app(AuthController::class)->details($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe([
        'full_name' => 'Jane Doe',
        'orders' => [],
    ]);
});

test('is-token-still-valid returns false for unauthenticated request', function () {
    $request = Request::create('/api/account/is-token-still-valid', 'POST');
    $request->setUserResolver(fn() => null);

    $response = app(AuthController::class)->isTokenStillValid($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe(['valid' => false]);
});

test('is-token-still-valid returns true for authenticated request', function () {
    $user = new User();
    $request = Request::create('/api/account/is-token-still-valid', 'POST');
    $request->setUserResolver(fn() => $user);

    $response = app(AuthController::class)->isTokenStillValid($request);

    expect($response->getStatusCode())->toBe(200);
    expect($response->getData(true))->toBe(['valid' => true]);
});
