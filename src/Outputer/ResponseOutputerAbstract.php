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

namespace W7\Http\Message\Outputer;

use W7\Http\Message\Server\Response;

abstract class ResponseOutputerAbstract implements ResponseOutputerInterface {
	protected $response = null;

	/**
	 * 设置标准响应对象
	 * @param ResponseOutputerAbstract $response
	 */
	public function withResponse(Response $response): void {
		$this->response = $response;
		return $this;
	}
}
