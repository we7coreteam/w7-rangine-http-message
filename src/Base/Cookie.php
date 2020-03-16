<?php

/**
 * Rangine Http Message
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * visited https://www.w7.cc for more details
 */

namespace W7\Http\Message\Base;

use Symfony\Component\HttpFoundation\Cookie as SymfonyCookie;

class Cookie extends SymfonyCookie {
	/**
	 * Default cookie properties
	 */
	public static $DEFAULTS = [
		'value'    => '',
		'domain'   => null,
		'path'     => '',
		'expires'  => 0,
		'secure'   => null,
		'httpOnly' => false
	];

	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
}
