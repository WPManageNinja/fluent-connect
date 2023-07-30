<?php
namespace FluentConnect\App\Services\Integrations;

class BootIntegrations
{
    public function init()
    {
        (new \FluentConnect\App\Services\Integrations\ThriveCart\ThriveCart())->init();
        (new \FluentConnect\App\Services\Integrations\WPCore\WPCoreInit())->init();
        (new \FluentConnect\App\Services\Integrations\FluentCRM\FluentCRMInit())->init();
    }
}
