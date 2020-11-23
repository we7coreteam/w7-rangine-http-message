<?php

/**
 * Rangine Http Message
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * This is not a free software
 * Using it under the license terms
 * visited https://www.w7.cc for more details
 */

namespace W7\Http\Message\Outputer;

use W7\Contract\Http\ResponseOutputerInterface;

abstract class ResponseOutputerAbstract implements ResponseOutputerInterface {
	protected $sendFd = null;

	public function disConnect() {
		return true;
	}

	public function withFd($fd) {
		if (empty($fd)) {
			return $this;
		}

		$this->sendFd = $fd;
		return $this;
	}
}
