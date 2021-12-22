<?php

namespace FluentConnect\App\Migrators\MailChimp;

use FluentConnect\App\Migrators\BaseMigrator;
use FluentConnect\Framework\Support\Arr;

class MailChimpMigrator extends BaseMigrator
{
    public function __construct()
    {
        parent::__construct('mailchimp', ['lists', 'tags']);
    }

    public function getLists()
    {
        $api = $this->getApiClass();
        $lists = $api->get('lists', array('count' => 9999));

        $formattedLists = [];

        foreach ($lists['lists'] as $list) {
            $formattedLists[$list['id']] = [
                'id'         => $list['id'],
                'name'       => $list['name'],
                'members'    => Arr::get($list, 'stats.member_count', 0),
                'created_at' => (new \DateTime(Arr::get($list, 'date_created', date('Y-m-d H:i:s'))))->format('Y-m-d H:i:s'),
            ];
        }

        return $formattedLists;
    }

    public function getTags($config = [])
    {
        $listId = Arr::get($config, 'list_id');

        $api = $this->getApiClass();
        $tags = $api->get('lists/' . $listId . '/segments', array(
            'count' => 1000,
            'type'  => 'static'
        ));

        $formattedTags = [];

        foreach ($tags['segments'] as $tag) {
            $formattedTags[$tag['id']] = [
                'id'         => $tag['id'],
                'name'       => $tag['name'],
                'members'    => Arr::get($tag, 'member_count', 0),
                'created_at' => (new \DateTime(Arr::get($tag, 'created_at', date('Y-m-d H:i:s'))))->format('Y-m-d H:i:s'),
                'updated_at' => (new \DateTime(Arr::get($tag, 'updated_at', date('Y-m-d H:i:s'))))->format('Y-m-d H:i:s'),
            ];
        }

        return $formattedTags;
    }

    public function getContactFields($config = [])
    {
        $listId = Arr::get($config, 'list_id');

        $api = $this->getApiClass();
        $customFields = $api->get('lists/' . $listId . '/merge-fields', array(
            'count' => 1000
        ));

        return $customFields;
    }

    public function getContacts($config = [], $limit = 20, $offset = 0)
    {
        $listId = Arr::get($config, 'list_id');
        $api = $this->getApiClass();
        $contacts = $api->get('lists/' . $listId . '/members', array(
            'count' => $limit,
            'offset' => $offset
        ));

        $formattedContacts = [];
        foreach ($contacts['members'] as $member) {
            $formattedContacts[] = [
                'ip_address' => $member['ip_signup'],
                'status' => $member['status'],
                'created_at' => $member['timestamp_signup'],
                'updated_at' => $member['last_changed'],
                'double_opt_at' => $member['timestamp_opt'],
                'double_opt_ip' => $member['ip_opt'],
                'contact_data' => array_merge($member['merge_fields'], [
                    'email' => $member['email_address'],
                    'full_name' => $member['full_name'],
                ]),
                'tag_maps' => array_map(function ($tag) {
                    return $tag['id'];
                }, $member['tags'])
            ];
        }

        return [
            'items' => $formattedContacts,
            'total' => $contacts['total_items'],
            'has_more' => $contacts['total_items'] > ($offset + $limit),
            'from' => $offset + 1,
            'to' => $offset + count($formattedContacts),
        ];
    }

    public function getContact($id)
    {
        // TODO: Implement getContact() method.
    }

    public function getApiClass()
    {
        return new MailChimp('c651e8aa16e126ec5d805e0b5da63ad4-us8');
    }
}
