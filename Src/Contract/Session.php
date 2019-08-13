<?php

/**
 * Rangine Http Message
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * visited https://www.w7.cc for more details
 */

namespace W7\Http\Message\Contract;

interface Session {
	public function setName($name);

	public function getName();

	public function setId($id);

	public function getId();

	public function getExpires($interval = false);

	public function set($key, $value);

	public function get($key, $default = '');

	public function has($key);

	public function destroy();
}