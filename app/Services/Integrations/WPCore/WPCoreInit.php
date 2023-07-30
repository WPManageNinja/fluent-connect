<?php

namespace FluentConnect\App\Services\Integrations\WPCore;

use FluentConnect\App\Services\ConnectStores;
use FluentConnect\App\Services\Integrations\WPCore\Triggers\ProfileUpdated;
use FluentConnect\App\Services\Integrations\WPCore\Triggers\UserLoggedIn;

class WPCoreInit
{
    public function init()
    {
        ConnectStores::addTriggerProvider('wp_core', [
            'title'                        => 'WordPress',
            'logo'                         => FLUENT_CONNECT_PLUGIN_URL . 'assets/images/wordpress.png',
            'description'                  => 'Do actions based WordPress related hooks',
            'require_integration_selector' => false,
            'enabled'                      => true
        ]);

        ConnectStores::addTrigger('wp_core', 'profile_update', ProfileUpdated::class);
        ConnectStores::addTrigger('wp_core', 'wp_login', UserLoggedIn::class);
    }
}
