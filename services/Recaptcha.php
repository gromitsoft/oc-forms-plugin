<?php

namespace GromIT\Forms\Services;

use Exception;
use GromIT\Forms\Models\Settings;
use InvalidArgumentException;
use October\Rain\Network\Http;

class Recaptcha
{
    private const SERVICE_URI = 'https://www.google.com/recaptcha/';

    /**
     * @var string
     */
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = Settings::getRecaptchaSecretKey();
    }

    /**
     * Checks captcha.
     *
     * @param string $captcha
     *
     * @return bool
     * @noinspection PhpComposerExtensionStubsInspection
     */
    public function check(string $captcha): ?bool
    {
        try {
            $response = Http::post(self::SERVICE_URI . 'api/siteverify', function (Http $http) use ($captcha) {
                $http->data('secret', $this->secretKey);
                $http->data('response', $captcha);
            });

            $body = json_decode($response->body, true);

            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new InvalidArgumentException(
                    'json_decode error: ' . json_last_error_msg()
                );
            }

            return (bool)$body['success'];
        } catch (Exception $exception) {
            return false;
        }
    }
}
