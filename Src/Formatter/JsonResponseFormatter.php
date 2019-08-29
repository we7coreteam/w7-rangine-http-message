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

use W7\Http\Message\Helper\JsonHelper;
use W7\Http\Message\Server\Response;

class JsonResponseFormatter implements ResponseFormatterInterface {
	public function formatter(Response $response): Response {
		// Headers
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'application/json');
		$response->getCharset() && $response = $response->withCharset($response->getCharset());

		// Content
		$data = $response->getData();
		if (!isset($data)) {
			$response = $response->withContent('{}');
		} else {
			$data = is_array($data) ? $data : ['data' => $data];
			$content = JsonHelper::encode($data, JSON_UNESCAPED_UNICODE);
			$response = $response->withContent($content);
		}

		return $response;
	}
}
