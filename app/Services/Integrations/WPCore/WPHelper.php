<?php

namespace FluentConnect\App\Services\Integrations\WPCore;

class WPHelper
{
    public static function getRoles()
    {
        global $wp_roles;
        $roles = $wp_roles->get_names();
        $roles = array_map(function ($role) {
            return [
                'id'    => $role,
                'title' => $role
            ];
        }, $roles);

        return $roles;
    }

    public static function getMockData($title = 'WordPress - User', $user = null)
    {
        if (!$user) {
            $user = get_user_by('ID', get_current_user_id());
        }

        if (!$user) {
            return null;
        }

        $dataOptions = [
            'ID'           => [
                'type'  => 'int',
                'title' => 'User ID',
                'mock'  => $user->ID
            ],
            'first_name'   => [
                'type'  => 'string',
                'title' => 'First Name',
                'mock'  => $user->first_name
            ],
            'last_name'    => [
                'type'  => 'string',
                'title' => 'Last Name',
                'mock'  => $user->last_name
            ],
            'user_email'   => [
                'type'  => 'string',
                'title' => 'User Email',
                'mock'  => $user->user_email
            ],
            'display_name' => [
                'type'  => 'string',
                'title' => 'Display Name',
                'mock'  => $user->display_name
            ],
            'user_login'   => [
                'type'  => 'string',
                'title' => 'User Login',
                'mock'  => $user->user_login
            ]
        ];

        return [
            'user' => [
                'status' => 'ok',
                'key'    => 'user',
                'title'  => $title,
                'mocks'  => $dataOptions
            ]
        ];
    }
}
