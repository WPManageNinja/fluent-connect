<?php

namespace FluentConnect\App\Services\Integrations\FluentCRM\Actions;

use FluentConnect\App\Services\BaseAction;
use FluentConnect\Framework\Support\Arr;
use FluentCrm\App\Models\Lists;
use FluentCrm\App\Models\Tag;

class CreateOrUpdateContact extends BaseAction
{
    public function __construct()
    {
        $this->actionProvider = 'fluentcrm';
        $this->actionName = 'fconnect_fluentcrm_create_or_update_contact';
    }

    public function getInfo()
    {
        return [
            'title'                  => 'Create or Update FluentCRM Contact',
            'description'            => 'This will add or update contact',
            'has_remote_integration' => false,
            'is_enabled'             => $this->isEnabled(),
            'disabled_message'       => 'Please install FluentCRM Plugin first',
            'settings_defaults'      => $this->getSettingsDefaults(),
            'required_data'          => [
                'email'
            ]
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'first_name'         => '',
            'last_name'          => '',
            'email'              => '',
            'list_ids'           => [],
            'tag_ids'            => [],
            'new_contact_status' => 'subscribed',
            'skip_if_exist'      => 'no'
        ];
    }

    public function getSettingsFields($action)
    {
        $statuses = fluentcrm_subscriber_editable_statuses();
        $formattedStatuses = [];
        foreach ($statuses as $status) {
            $formattedStatuses[] = [
                'label' => ucfirst($status),
                'id'    => $status
            ];
        }

        return [
            'first_name'         => [
                'type'        => 'input-data',
                'label'       => 'First Name',
                'placeholder' => 'Contact First Name'
            ],
            'last_name'          => [
                'type'        => 'input-data',
                'label'       => 'Last Name',
                'placeholder' => 'Contact Last Name'
            ],
            'email'              => [
                'type'        => 'input-data',
                'label'       => 'Email',
                'placeholder' => 'Contact Email Address'
            ],
            'list_ids'           => [
                'type'        => 'input-options',
                'label'       => 'Select Lists',
                'is_multiple' => true,
                'placeholder' => 'Select FluentCRM Lists',
                'options'     => Lists::orderBy('title', 'ASC')->select(['id', 'title'])->get()
            ],
            'tag_ids'            => [
                'type'        => 'input-options',
                'label'       => 'Select Tags',
                'is_multiple' => true,
                'placeholder' => 'Select FluentCRM Tags',
                'options'     => Tag::orderBy('title', 'ASC')->select(['id', 'title'])->get()
            ],
            'new_contact_status' => [
                'type'        => 'input-radio',
                'label'       => 'Status for new contacts',
                'inline_help' => 'Existing contact status will not be changed',
                'options'     => $formattedStatuses
            ],
            'skip_if_exist'      => [
                'checkbox_label' => 'Skip this task if contact already exist in the FluentCRM database',
                'true_label'     => 'yes',
                'false_label'    => 'false'
            ]
        ];
    }

    public function process($action, $data)
    {
        $data['status'] = Arr::get($action->settings, 'new_contact_status');
        $contact = Helper::createOrUpdateContact($data, Arr::get($action->settings, 'new_contact_status') == 'yes');

        if (is_wp_error($contact)) {
            return $contact;
        }

        if (!$contact) {
            return new \WP_Error('failed', 'Contact could not be created on FluentCRM');
        }

        $contact->attachLists(Arr::get($action->settings, 'list_ids', []));
        $contact->attachTags(Arr::get($action->settings, 'tag_ids', []));
        $message = 'Contact has been updated in FluentCRM';

        if ($contact->wasRecentlyCreated) {
            $message = 'Contact has been created in FluentCRM';
        }

        return [
            'status'           => 'completed',
            'message'          => $message,
            'remote_action_id' => $contact->id,
            'reference_url'    => Helper::getContactViewUrl($contact->id)
        ];
    }
}
