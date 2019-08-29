<?php

/**
 * WeEngine Api System
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\Http\Message\Base;

trait CookieTrait {
	/**
	 * @var array
	 */
	protected $cookies = [];

	/**
	 * @param string $name
	 * @param $value
	 * @return CookieTrait
	 */
	public function setCookie(string $name, $value): self {
		if (is_string($value)) {
			$cookie = new Cookie([
				'name' => $name,
				'value' => $value
			]);
			$this->cookies[$name] = $cookie;
		} elseif (is_object($value) && $value instanceof Cookie) {
			$this->cookies[$name] = $value;
		} elseif (is_array($value)) {
			$value['name'] = $value['name'] ?? $name;
			$cookie = new Cookie($value);
			$this->cookies[$name] = $cookie;
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @param $value
	 * @return CookieTrait
	 */
	public function withCookie($name, $value = ''): self {
		//兼容旧版用法
		if ($name instanceof Cookie) {
			$value = $name;
			$name = $name->getName();
		}
		$new = clone $this;
		$new->setCookie($name, $value);

		return $new;
	}

	/**
	 * @param string $name
	 * @return CookieTrait
	 */
	public function delCookie(string $name): self {
		if (isset($this->cookies[$name])) {
			unset($this->cookies[$name]);
		}

		return $this;
	}

	/**
	 * @param string $name
	 * @return CookieTrait
	 */
	public function withoutCookie(string $name): self {
		$new = clone $this;
		$new->delCookie($name);

		return $new;
	}

	/**
	 * @return array
	 */
	public function getCookies(): array {
		return $this->cookies;
	}

	/**
	 * @param array $cookies
	 * @return CookieTrait
	 */
	public function setCookies(array $cookies): self {
		if (!$cookies) {
			$this->cookies = [];
			return $this;
		}

		foreach ($cookies as $name => $value) {
			$this->setCookie($name, $value);
		}

		return $this;
	}

	/**
	 * @param array $cookies
	 *
	 * @return $this
	 */
	public function withCookies(array $cookies): self {
		$new = clone $this;
		$new->setCookies($cookies);

		return $new;
	}

	/**
	 * @return CookieTrait
	 */
	public function withoutCookies(): self {
		$new = clone $this;
		$new->setCookies([]);

		return $new;
	}
}
