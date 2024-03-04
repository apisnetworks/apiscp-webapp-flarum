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

	use Module\Support\Webapps\App\Type\Flarum\Walker;
	use Module\Support\Webapps\App\Type\Unknown\Reconfiguration\Ssl as SslParent;
	use Module\Support\Webapps\Contracts\DeferredReconfiguration;

	class Ssl extends SslParent implements DeferredReconfiguration
	{
		public function apply(mixed &$val): bool
		{
			$val = (bool)$val;
			try {
				$cfg = Walker::instantiateContexted($this->getAuthContext(),
					[$this->app->getAppRoot() . '/config.php']);

				$url = $cfg->get('url');
				return $cfg->set('url', preg_replace('!^https?(?=://)!', $val ? 'https' : 'http', $url))->save();
			} catch (\Exception $e) {
				return false;
			}
		}


	}