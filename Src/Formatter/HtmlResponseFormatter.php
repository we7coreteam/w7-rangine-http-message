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

use W7\Http\Message\Server\Response;

class HtmlResponseFormatter extends RawResponseFormatter {
	public function formatter(Response $response): Response {
		$response = parent::formatter($response);

		// Headers
		$response = $response->withoutHeader('Content-Type')->withAddedHeader('Content-Type', 'text/html');
		$response->getCharset() && $response = $response->withCharset($response->getCharset());

		return $response;
	}
}
