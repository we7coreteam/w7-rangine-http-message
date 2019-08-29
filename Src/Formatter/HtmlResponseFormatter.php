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
