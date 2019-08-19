<?php

/**
 * Rangine Http Message
 *
 * (c) We7Team 2019 <https://www.w7.cc>
 *
 * visited https://www.w7.cc for more details
 */

namespace W7\Http\Message\Base;

class Cookie {
	/**
	 * Default cookie properties
	 */
	public const DEFAULTS = [
		'value'    => '',
		'domain'   => '',
		'path'     => '',
		'expires'  => 0,
		'secure'   => false,
		'httpOnly' => false
	];

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $value = '';

	/**
	 * @var string
	 */
	private $path = '';

	/**
	 * @var string
	 */
	private $domain = '';

	/**
	 * @var int
	 */
	private $expires = 0;

	/**
	 * @var bool
	 */
	private $secure  = false;

	/**
	 * @var bool
	 */
	private $httpOnly = false;

	/**
	 * @param array $config
	 *
	 * @return self
	 * @throws ReflectionException
	 * @throws ContainerException
	 */
	public static function new(array $config = []): self {
		$self = new static();

		if ($config) {
			$self->init($config);
		}

		return $self;
	}

	/**
	 * Set the property value for the object
	 * - Will try to set properties using the setter method
	 * - Then, try to set properties directly
	 *
	 * @param mixed $object An object instance
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function init(array $options) {
		foreach ($options as $property => $value) {
			if (is_numeric($property)) {
				continue;
			}

			$setter = 'set' . ucfirst($property);

			// has setter
			if (method_exists($this, $setter)) {
				$this->$setter($value);
			} elseif (property_exists($this, $property)) {
				$this->$property = $value;
			}
		}
	}

	/**
	 * @return array
	 */
	public function toArray(): array {
		return [
			'value'    => $this->value,
			'domain'   => $this->domain,
			'path'     => $this->getPath(),
			'expires'  => $this->getExpires(),
			'secure'   => $this->isSecure(),
			'httpOnly' => $this->isHttpOnly()
		];
	}

	/**
	 * @return string
	 */
	public function toString(): string {
		$result = urlencode($this->name) . '=' . urlencode($this->value);

		if ($this->getDomain()) {
			$result .= '; domain=' . $this->getDomain();
		}

		if ($this->getPath()) {
			$result .= '; path=' . $this->getPath();
		}

		if ($timestamp = $this->getExpires()) {
			$result .= '; expires=' . gmdate('D, d-M-Y H:i:s e', $timestamp);
		}

		if ($this->isSecure()) {
			$result .= '; secure';
		}

		if ($this->isHttpOnly()) {
			$result .= '; HttpOnly';
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->toString();
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return Cookie
	 */
	public function setName(string $name): Cookie {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue(): string {
		return $this->value;
	}

	/**
	 * @param string $value
	 *
	 * @return Cookie
	 */
	public function setValue(string $value): Cookie {
		$this->value = $value;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath(): string {
		return empty($this->path) ? ini_get('session.cookie_path') : $this->path;
	}

	/**
	 * @param string $path
	 *
	 * @return Cookie
	 */
	public function setPath(string $path): Cookie {
		$this->path = $path;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDomain(): string {
		return empty($this->domain) ? ini_get('session.cookie_domain') : $this->domain;
	}

	/**
	 * @param string $domain
	 *
	 * @return Cookie
	 */
	public function setDomain(string $domain): Cookie {
		$this->domain = $domain;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getExpires(): int {
		return empty($this->expires) ? (time() + ini_get('session.cookie_lifetime')) : $this->expires;
	}

	/**
	 * @param int $expires
	 *
	 * @return Cookie
	 */
	public function setExpires(int $expires): Cookie {
		$this->expires = $expires;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSecure(): bool {
		return empty($this->secure) ? ini_get('session.cookie_secure') : $this->secure;
	}

	/**
	 * @param bool $secure
	 *
	 * @return Cookie
	 */
	public function setSecure(bool $secure): Cookie {
		$this->secure = $secure;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isHttpOnly(): bool {
		return empty($this->httpOnly) ? ini_get('session.cookie_httponly') : $this->httpOnly;
	}

	/**
	 * @param bool $httpOnly
	 *
	 * @return Cookie
	 */
	public function setHttpOnly(bool $httpOnly): Cookie {
		$this->httpOnly = $httpOnly;
		return $this;
	}
}
