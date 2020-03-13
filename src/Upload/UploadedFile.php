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

namespace W7\Http\Message\Upload;

use Psr\Http\Message\UploadedFileInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

/**
 * Class Request
 * @package W7\Http\Message\Server
 */
class UploadedFile extends SymfonyUploadedFile implements UploadedFileInterface {

	public function getStream() {
		if (!$this->isFile() || empty($this->getRealPath())) {
			throw new FileNotFoundException($this->getRealPath());
		}
		return file_get_contents($this->getRealPath());
	}

	public function moveTo($targetPath) {
		return $this->move($targetPath);
	}

	public function getSize() {
		return parent::getSize();
	}

	public function getError() {
		return parent::getError();
	}

	public function getClientFilename() {
		return $this->getClientOriginalName();
	}

	public function getClientMediaType() {
		return $this->getMimeType();
	}
}
