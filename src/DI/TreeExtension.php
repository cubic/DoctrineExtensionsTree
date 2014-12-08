<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\DoctrineExtensionsTree\DI;

use Doctrine\Common\Annotations\Reader;
use Gedmo\Tree\TreeListener;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;


class TreeExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setClass(TreeListener::class)
			->addSetup('setAnnotationReader', ['@' . Reader::class])
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}

}
