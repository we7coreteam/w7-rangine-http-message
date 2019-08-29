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

namespace W7\Http\Message\Formatter;

use W7\Contract\Arrayable;
use W7\Http\Message\Helper\JsonHelper;
use W7\Http\Message\Server\Response;

class RawResponseFormatter implements ResponseFormatterInterface {
	public function formatter(Response $response): Response {
		// Headers
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/plain');
		$response->getCharset() && $response = $response->withCharset($response->getCharset());

		// Content
		$content = '';
		$data = $response->getData();
		if (is_scalar($data)) {
			$content = (string)$data;
		}
		if (is_object($data)) {
			$content = '';
			if (method_exists($data, '__toString')) {
				$content = (string)$data;
			}
			if ($data instanceof Arrayable) {
				$data = $data->toArray();
			}
		}
		if (is_array($data)) {
			$content = JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
		}
		$response = $response->withContent($content);

		return $response;
	}
}
