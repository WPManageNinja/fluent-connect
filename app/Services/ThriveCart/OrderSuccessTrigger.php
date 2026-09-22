<?php

namespace FluentConnect\App\Services\ThriveCart;

use FluentConnect\App\Services\Triggers\BaseTrigger;
use FluentConnect\Framework\Support\Arr;

class OrderSuccessTrigger extends BaseTrigger
{
    public function __construct()
    {
        $this->triggerProvider = 'thrivecart';
        $this->triggerName = 'fluent_connector_thrivecart_event_order_success';
        parent::__construct();
    }

    public function getInfo()
    {
        return [
            'title'                  => 'Order success on ThriveCart',
            'description'            => 'This will trigger when a purchase made on ThriveCart',
            'has_remote_integration' => true,
            'supported_fields'       => [
                'email',
                'name',
                'first_name',
                'last_name'
            ],
            'is_enabled'             => $this->isEnabled(),
            'disabled_message'       => 'Please configure ThriveCart integration first',
            'settings_defaults'      => $this->getSettingsDefaults()
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'match_type'  => 'product_ids',
            'product_ids' => [],
            'match_all'   => 'no'
        ];
    }

    public function getSettingsFields($trigger)
    {
        $groupedProducts = ThriveCartHelper::getGroupedProductsOptions($trigger->integration_id);
        return [
            'product_ids' => [
                'type'        => 'input-grouped-options',
                'label'       => 'Select Target ThriveCart Products',
                'inline_help' => 'Select the products that you want to run this feed. Keep blank for any purchases on ThriveCart',
                'placeholder' => 'Select Products',
                'options'     => $groupedProducts,
                'show_id'     => true,
                'is_multiple' => true
            ],
            'match_all'   => [
                'type'           => 'inline-checkbox',
                'true_label'     => 'yes',
                'false_label'    => 'no',
                'checkbox_label' => 'Run only if all the selected products has been purchased on the order'
            ]
        ];
    }

    public function getFormattedData($trigger, $args)
    {
        $event = $args[0];
        $remoteAccount = Arr::get($event, 'thrivecart_account', '');

        $trigger->load('integration');

        if ($remoteAccount != $trigger->integration->remote_id) {
            error_log('[FluentConnect][ThriveCart] Webhook ignored: account mismatch');
            return false; // Not Our Account
        }
        // check the product match
        if ($targetProductIds = Arr::get($trigger->settings, 'product_ids', [])) {

            $purchaseMap = Arr::get($event, 'purchase_map', Arr::get($event, 'accessible_purchase_map', []));
            $intersected = array_intersect((array) $purchaseMap, $targetProductIds);
            if (!$intersected) {
                error_log('[FluentConnect][ThriveCart] Webhook ignored: product mismatch');
                return false;
            }

            if (Arr::get($trigger->settings, 'match_all') == 'yes') {
                if(count($intersected) != count($targetProductIds)) {
                    return false;
                }
            }
        }

        $data = ThriveCartHelper::getDataFromCustomer(Arr::get($event, 'customer', []));

        if($data) {
            $data['__runner_hash'] = $event['order_id'];
            $data['__event'] = $event;
        } else {
            error_log('[FluentConnect][ThriveCart] Webhook ignored: missing customer data');
        }

        return $data;
    }

    public function isEnabled()
    {
        return true;
    }
}
