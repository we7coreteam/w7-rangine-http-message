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

namespace W7\Http\Message\Server;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Psr\Http\Message\ResponseInterface;
use W7\Contract\Arrayable;
use W7\Http\Message\Base\Cookie;
use W7\Http\Message\Base\CookieTrait;
use w7\Http\Message\File\File;
use W7\Http\Message\Formatter\HtmlResponseFormatter;
use W7\Http\Message\Formatter\JsonResponseFormatter;
use W7\Http\Message\Formatter\RawResponseFormatter;
use W7\Http\Message\Formatter\ResponseFormatterInterface;
use W7\Http\Message\Outputer\ResponseOutputerInterface;
use W7\Http\Message\Stream\SwooleStream;

/**
 * Class Request
 * @package W7\Http\Message\Server
 */
class Response extends \W7\Http\Message\Base\Response implements ResponseInterface {
	use CookieTrait;

	/**
	 * Original response data. When this is not null, it will be converted into stream content
	 *
	 * @var mixed
	 */
	protected $data;

	/**
	 * @var File
	 */
	protected $file;

	/**
	 * @var \Throwable|null
	 */
	protected $exception;

	/**
	 * @var ResponseFormatterInterface
	 */
	protected $formatter;

	/**
	 * @var ResponseOutputerInterface
	 */
	protected $outputer;

	public function __construct() {

	}

	public function setFormatter(ResponseFormatterInterface $formatter) {
		$this->formatter = $formatter;
	}

	public function getFormatter() : ResponseFormatterInterface {
		if (!$this->formatter) {
			$this->formatter = new JsonResponseFormatter();
		}

		return $this->formatter;
	}

	/**
	 * 设置输出器，Swoole Fpm WebSocket Tcp
	 * @param ResponseOutputerInterface $outputer
	 */
	public function setOutputer(ResponseOutputerInterface $outputer): void {
		$this->outputer = $outputer;
	}

	/**
	 * @return ResponseOutputerInterface
	 */
	private function getOutputer(): ResponseOutputerInterface {
		return $this->outputer;
	}

	/**
	 * Redirect to a URL
	 *
	 * @param string   $url
	 * @param null|int $status
	 * @return static
	 */
	public function redirect($url, $status = 302) {
		$response = $this;
		$response = $response->withAddedHeader('Location', (string)$url)->withStatus($status);
		return $response;
	}

	/**
	 * @param string $data
	 * @return Response
	 */
	public function raw(string $data = ''): Response {
		$this->setFormatter(new RawResponseFormatter());
		return $this->withData($data);
	}

	/**
	 * @param array $data
	 * @return Response
	 */
	public function json($data = []): Response {
		$this->setFormatter(new JsonResponseFormatter());
		return $this->withData($data);
	}

	/**
	 * @param string $data
	 * @return Response
	 */
	public function html(string $data = ''): Response {
		$this->setFormatter(new HtmlResponseFormatter());
		return $this->withData($data);
	}

	/**
	 * Return instance with the specified data
	 *
	 * @param mixed $data
	 *
	 * @return static
	 */
	public function withData($data) {
		$clone = clone $this;

		$clone->data = $data;
		$clone = $clone->getFormatter()->formatter($clone);
		return $clone;
	}

	/**
	 * @return mixed
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * 不关闭连接，持续向客户端写入数据
	 * 相当于 flush() 强制输出缓冲区内容到浏览器
	 * 也可以用于发送Chunk数据
	 */
	public function write() {
		$this->withSendHeader();
		$this->getOutputer()->sendChunk($this->getBody()->getContents());
	}

	/**
	 * 处理 Response 并发送数据
	 */
	public function send() {
		$this->withSendHeader();

		/**
		 * 发送文件, 调用 sendfile 不必再调用 send
		 */
		if (!empty($this->file)) {
			return $this->getOutputer()->sendFile($this->file);
		} else {
			return $this->getOutputer()->sendBody($this->getBody()->getContents());
		}
	}

	/**
	 * 设置Body内容，使用默认的Stream
	 *
	 * @param string $content
	 * @return static
	 */
	public function withContent($content): Response {
		$new = clone $this;
		$new->stream = new SwooleStream($content);
		return $new;
	}

	public function withFile(File $file) {
		$clone = clone $this;
		$clone->file = $file;
		return $clone;
	}

	/**
	 * @return null|\Throwable
	 */
	public function getException() {
		return $this->exception;
	}

	/**
	 * @param \Throwable $exception
	 * @return $this
	 */
	public function setException(\Throwable $exception) {
		$this->exception = $exception;
		return $this;
	}

	/**
	 * @param mixed $value
	 * @return bool
	 */
	public function isArrayable($value): bool {
		return is_array($value) || $value instanceof Arrayable;
	}

	/**
	 * @param string $accept
	 * @param string $keyword
	 * @return bool
	 */
	public function isMatchAccept(string $accept, string $keyword): bool {
		return strpos($accept, $keyword) !== false;
	}

	private function withSendHeader() {
		$this->getOutputer()->sendHeader($this->getHeaders());
		$this->getOutputer()->sendCookie($this->getCookies());
		$this->getOutputer()->sendStatus($this->getStatusCode());
		return $this;
	}
}
