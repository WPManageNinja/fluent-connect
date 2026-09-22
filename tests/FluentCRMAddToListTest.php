<?php

namespace FluentConnect\App\Services\Actions {
    class BaseAction
    {
    }
}

namespace FluentConnect\Framework\Support {
    class Arr
    {
        public static function get($array, $key, $default = null)
        {
            return isset($array[$key]) ? $array[$key] : $default;
        }
    }
}

namespace FluentConnect\App\Services\Actions\FluentCRM {
    class Helper
    {
        public static $contact;

        public static function createOrUpdateContact($data, $skipIfExists)
        {
            return self::$contact;
        }

        public static function getContactViewUrl($contactId)
        {
            return '';
        }
    }
}

namespace {
    function is_wp_error($value)
    {
        return false;
    }

    require_once dirname(__DIR__) . '/app/Services/Actions/FluentCRM/AddToList.php';

    class TestContact
    {
        public $id = 1;
        public $wasRecentlyCreated = true;
        public $attachedLists = [];

        public function attachLists($listIds)
        {
            $this->attachedLists = $listIds;
        }
    }

    $contact = new TestContact();
    \FluentConnect\App\Services\Actions\FluentCRM\Helper::$contact = $contact;

    $action = (object) [
        'settings' => [
            'list_ids' => [81],
            'new_contact_status' => 'subscribed'
        ]
    ];

    $handler = new \FluentConnect\App\Services\Actions\FluentCRM\AddToList();
    $handler->process($action, ['email' => 'fresh@example.test']);

    if ($contact->attachedLists !== [81]) {
        throw new \RuntimeException('Configured list IDs were not attached to the contact.');
    }

    echo "FluentCRM add-to-list test passed.\n";
}
