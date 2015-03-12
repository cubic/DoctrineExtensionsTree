<?php

namespace Zenify\DoctrineExtensionsTree\Tests;

use Nette;
use Nette\DI\Container;


class ContainerFactory
{

	/**
	 * @return Container
	 */
	public function create()
	{
		$configurator = new Nette\Configurator;
		$configurator->setTempDirectory(TEMP_DIR);
		$configurator->addConfig(__DIR__ . '/config/default.neon');
		return $configurator->createContainer();
	}

}
