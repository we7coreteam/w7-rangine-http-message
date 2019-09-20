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
			ini_set('session.cookie_httponly', $config['http_only']);
		}
		if (isset($config['path'])) {
			ini_set('session.cookie_path', $config['path']);
		}
		if (isset($config['domain'])) {
			ini_set('session.cookie_domain', $config['domain']);
		}
		if (isset($config['secure'])) {
			ini_set('session.cookie_secure', $config['secure']);
		}
		$config = $this->config->getUserAppConfig('session');
		if (isset($config['expires']) && $config['expires'] >= 0) {
			ini_set('session.cookie_lifetime', (int)$config['expires']);
		}
	}

	private function setResponseFormatter() {
		iloader()->set(ResponseFormatterInterface::class, function () {
			return new JsonResponseFormatter();
		});
	}
}
