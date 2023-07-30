<?php

/**
 * All registered action's handlers should be in app\Hooks\Handlers,
 * addAction is similar to add_action and addCustomAction is just a
 * wrapper over add_action which will add a prefix to the hook name
 * using the plugin slug to make it unique in all wordpress plugins,
 * ex: $app->addCustomAction('foo', ['FooHandler', 'handleFoo']) is
 * equivalent to add_action('slug-foo', ['FooHandler', 'handleFoo']).
 */

/**
 * @var $app FluentConnect\Framework\Foundation\Application
 */

$app->addAction('admin_menu', 'AdminMenuHandler@add');

$app->addAction('init', 'WebhookReceiverHandler@route');

/*
 * Init Integration Providers
 */
(new \FluentConnect\App\Services\Integrations\BootIntegrations())->init();

(new \FluentConnect\App\Hooks\Handlers\TriggerInitHandler())->init();
