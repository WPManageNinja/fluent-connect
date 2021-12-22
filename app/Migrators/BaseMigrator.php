<?php

namespace FluentConnect\App\Migrators;

abstract class BaseMigrator
{
    protected $provider;

    protected $supports = [];

    public function __construct($provider, $supports = [])
    {
        $this->provider = $provider;
        $this->supports = $supports;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function hasSupport($support)
    {
        return in_array($support, $this->supports);
    }

    public function getLists()
    {
        return [];
    }

    abstract public function getTags($config = []);

    abstract public function getContactFields($config = []);

    abstract public function getContacts($config = [], $limit = 20, $offset = 0);

    abstract public function getContact($id);
}
