<?php

/**
 * Rangine Http Message
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */

namespace W7\Http\Message;

use W7\Core\Provider\ProviderAbstract;
use W7\Http\Message\Base\Cookie;
use W7\Http\Message\Formatter\JsonResponseFormatter;
use W7\Http\Message\Formatter\ResponseFormatterInterface;

class ServiceProvider extends ProviderAbstract {
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->initCookieEnv();
		$this->setResponseFormatter();
	}

	private function initCookieEnv() {
		$config = $this->config->getUserAppConfig('cookie');

		if (isset($config['http_only'])) {
			Cookie::$DEFAULTS['httpOnly'] = $config['http_only'];
		}
		if (isset($config['path'])) {
			Cookie::$DEFAULTS['path'] = $config['path'];
		}
		if (isset($config['domain'])) {
			Cookie::$DEFAULTS['domain'] = $config['domain'];
		}
		if (isset($config['secure'])) {
			Cookie::$DEFAULTS['secure'] = $config['secure'];
		}
		$config = $this->config->getUserAppConfig('session');
		if (isset($config['expires']) && $config['expires'] >= 0) {
			Cookie::$DEFAULTS['expires'] = (int)$config['expires'];
		}
	}

	private function setResponseFormatter() {
		if (method_exists(iloader(), 'set')) {
			iloader()->set(ResponseFormatterInterface::class, function () {
				return new JsonResponseFormatter();
			});
		}
	}
}
