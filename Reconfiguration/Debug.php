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
	 * Written by Matt Saladna <matt@apisnetworks.com>, March 2024
	 */

	namespace Module\Support\Webapps\App\Type\Flarum\Reconfiguration;

	use Module\Support\Webapps\App\Reconfigurator;
	use Module\Support\Webapps\App\Type\Flarum\Walker;
	use Module\Support\Webapps\Contracts\ReconfigurableProperty;

	class Debug extends Reconfigurator implements ReconfigurableProperty
	{
		public function handle(&$val): bool
		{
			$val = (bool)$val;
			try {
				$cfg = Walker::instantiateContexted($this->getAuthContext(),
					[$this->app->getAppRoot() . '/config.php']);
				return $cfg->set('debug', $val)->save();
			} catch (\Exception $e) {
				return false;
			}
		}

		public function getValue()
		{
			$cfg = Walker::instantiateContexted($this->getAuthContext(),
				[$this->app->getAppRoot() . '/config.php']);

			return (bool)$cfg->get('debug');
		}


	}