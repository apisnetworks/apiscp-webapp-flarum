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

	use Module\Support\Webapps\App\Type\Laravel\Reconfiguration\Verlock as VerlockParent;

	class Verlock extends VerlockParent
	{
		const PACKAGE_NAME = 'flarum/core';
	}