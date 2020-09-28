<?php

use Hybridauth\Hybridauth;
use MyCLabs\Enum\Enum;

class SocialAuthentication extends CApplicationComponent
{
    protected $instance;
    protected $adapter;
    public $config;

    public function init()
    {
        $this->instance = new Hybridauth($this->config);
    }

    public function auth(AuthType $type): void
    {
        $this->adapter = $this->instance->authenticate($type);
    }

    public function getProfile(): object
    {
        return $this->adapter->getUserProfile();
    }
}


/**
 * @method static self FACEBOOK()
 * @method static self GOOGLE()
 * @method static self LINKEDIN()
 */
class AuthType extends Enum
{
    private const FACEBOOK = 'Facebook';
    private const GOOGLE = 'Google';
    private const LINKEDIN = 'LinkedIn';
}
