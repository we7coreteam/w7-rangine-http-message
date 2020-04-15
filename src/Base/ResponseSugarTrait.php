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

namespace W7\Http\Message\Base;

use W7\Contract\Arrayable;
use W7\Http\Message\Helper\JsonHelper;
use W7\Http\Message\Server\Response;

trait ResponseSugarTrait {
	/**
	 * Redirect to a URL
	 *
	 * @param string $url
	 * @param null|int $status
	 * @return static
	 */
	public function redirect($url, $status = 302) {
		$response = $this;
		$response = $response->withAddedHeader('Location', (string)$url)->withStatus($status);
		return $response;
	}

	/**
	 * return a Raw format response
	 *
	 * @param  string $data   The data
	 * @param  int    $status The HTTP status code.
	 * @return static
	 */
	public function raw(string $data = '', int $status = 200) {
		$response = $this;
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/plain');
		$this->getCharset() && $response = $response->withCharset($this->getCharset());
		($data || is_numeric($data)) && $response = $response->withContent($data);
		$status && $response = $response->withStatus($status);
		return $response;
	}

	/**
	 * return a Json format response
	 *
	 * @param  array|Arrayable $data            The data
	 * @param  int             $status          The HTTP status code.
	 * @param  int             $encodingOptions Json encoding options
	 * @return static
	 * @throws \InvalidArgumentException
	 */
	public function json($data = [], int $status = 200, int $encodingOptions = JSON_UNESCAPED_UNICODE) {
		$response = $this;

		// Headers
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'application/json');
		$this->getCharset() && $response = $response->withCharset($this->getCharset());

		// Content
		if (($data || is_numeric($data)) && ((is_array($data) || ($data instanceof \ArrayIterator) || $data instanceof Arrayable) || is_string($data))) {
			is_string($data) && $data = ['data' => $data];
			$content = JsonHelper::encode($data, $encodingOptions);
			$response = $response->withContent($content);
		} else {
			$response = $response->withContent('{}');
		}

		// Status code
		$status && $response = $response->withStatus($status);

		return $response;
	}
}
