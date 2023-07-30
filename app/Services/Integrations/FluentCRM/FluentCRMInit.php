<?php
namespace FluentConnect\App\Services\Integrations\FluentCRM;

use FluentConnect\App\Services\ConnectStores;
use FluentConnect\App\Services\Integrations\FluentCRM\Actions\AddToList;
use FluentConnect\App\Services\Integrations\FluentCRM\Actions\AddToTag;
use FluentConnect\App\Services\Integrations\FluentCRM\Actions\RemoveFromList;
use FluentConnect\App\Services\Integrations\FluentCRM\Actions\RemoveFromTag;

class FluentCRMInit
{
    public function init()
    {
        ConnectStores::addActionProvider('fluentcrm', [
            'title'                        => 'FluentCRM',
            'logo'                         => FLUENT_CONNECT_PLUGIN_URL . 'assets/images/fluentcrm.png',
            'description'                  => 'Add contact, tags, lists and other actions on FluentCRM',
            'require_integration_selector' => false,
            'enabled'                      => true
        ]);

        ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_add_tag', AddToTag::class);
        ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_add_to_list', AddToList::class);
        ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_remove_tag', RemoveFromTag::class);
        ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_remove_from_list', RemoveFromList::class);
    }
}
