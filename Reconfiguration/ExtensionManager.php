<?php declare(strict_types=1);
	/*
	 * Copyright (C) Apis Networks, Inc - All Rights Reserved.
	 *
	 * Unauthorized copying of this file, via any medium, is
	 * strictly prohibited without consent. Any dissemination of
	 * material herein is prohibited.
	 *
	 * For licensing inquiries email <licensing@apisnetworks.com>
	 *
	 * Written by Matt Saladna <matt@apisnetworks.com>, February 2024
	 */

	namespace Module\Support\Webapps\App\Type\Flarum\Reconfiguration;

	use Module\Support\Webapps\App\Reconfigurator;
	use Module\Support\Webapps\App\Type\Unknown\Handler as Unknown;
	use Module\Support\Webapps\ComposerMetadata;
	use Module\Support\Webapps\Contracts\DeferredReconfiguration;
	use Module\Support\Webapps\Contracts\ReconfigurableProperty;
	use Module\Support\Webapps\Traits\WebappUtilities;

	class ExtensionManager extends Reconfigurator implements ReconfigurableProperty, DeferredReconfiguration
	{
		use WebappUtilities;

		public function __construct(Unknown $app, ?\Deferred $context = null)
		{
			parent::__construct($app, $context);
		}


		public function handle(&$val): bool
		{
			$val = (bool)$val;

			return $this->app->isInstalling() || version_compare($ver = $this->app->getVersion(), '1.8.0', '>=') ?:
				error("%(app)s does not minimum version requirement %(version)s", ['app' => $this->app->getName(), 'version' => $ver]);
		}

		public function apply(mixed &$val): bool
		{
			return $this->{'flarum_' . ($val ? 'install' : 'uninstall') . '_plugin'}($this->app->getHostname(), $this->app->getPath(), $this->getPackageName());
		}

		public function getValue()
		{
			if ($this->app->isInstalling()) {
				return null;
			}
			$composer = ComposerMetadata::readFrozen($this->getAuthContextFromDocroot($approot = $this->app->getAppRoot()), $approot);
			return (bool)array_first($composer['packages'], fn($p) => $p['name'] === $this->getPackageName());
		}


		private function getPackageName(): string
		{
			return version_compare($this->app->getVersion(), '1.8.0', '>=') ? 'flarum/extension-manager' : 'flarum/package-manager';
		}
	}