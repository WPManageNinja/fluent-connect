<?php

namespace FluentConnect\App\Services\Integrations\WPCore\Triggers;

use FluentConnect\App\Services\BaseTrigger;
use FluentConnect\App\Services\Integrations\WPCore\WPHelper;
use FluentConnect\Framework\Support\Arr;

class UserLoggedIn extends BaseTrigger
{
    protected $triggerArgs = 2;

    public function __construct()
    {
        $this->triggerProvider = 'wp_core';
        $this->triggerName = 'wp_login';
        parent::__construct();
    }

    public function getInfo()
    {
        return [
            'title'                  => 'User logged in',
            'description'            => 'This will trigger when a user login to your site',
            'has_remote_integration' => false,
            'supported_fields'       => [
                'email',
                'user_id',
                'first_name',
                'last_name'
            ],
            'is_enabled'             => $this->isEnabled(),
            'settings_defaults'      => $this->getSettingsDefaults()
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'user_roles' => []
        ];
    }

    public function getSettingsFields($trigger)
    {
        return [
            'user_roles' => [
                'type'        => 'input-options',
                'label'       => 'Select Target User Role',
                'placeholder' => 'Select User Roles (Leave blank for all)',
                'options'     => [
                    [
                        'id'    => 'administrator',
                        'title' => 'Administrator'
                    ]
                ],
                'show_id'     => false,
                'is_multiple' => true
            ]
        ];
    }

    public function isEnabled()
    {
        return true;
    }

    public function getFormattedData($trigger, $args)
    {
        $user = $args[2];

        if (!$user) {
            return false;
        }

        if (!$this->conditionMatched($trigger, $args)) {
            return false;
        }

        return [
            'first_name'    => $user->first_name,
            'last_name'     => $user->last_name,
            'name'          => $user->display_name,
            'email'         => $user->user_email,
            'user_id'       => $user->ID,
            '__user'        => $user,
            '__runner_hash' => $user->ID
        ];
    }

    public function getSchema($trigger)
    {
        return WPHelper::getMockData('WordPress - User (Login)');
    }

    public function conditionMatched($trigger, $args)
    {
        $user = get_user_by('ID', $args[0]);

        if (!$user) {
            return false;
        }

        $targetRoles = Arr::get($trigger->settings, 'user_roles', []);

        if ($targetRoles) {
            if (!array_intersect((array)$user->roles, $targetRoles)) {
                return false;
            }
        }

        return true;
    }
}
