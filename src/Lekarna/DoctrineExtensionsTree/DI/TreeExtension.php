<?php

/**
 * This file is part of Lekarna.cz (http://www.lekarna.cz/)
 *
 * Copyright (c) 2014 Pears Health Cyber, s.r.o. (http://pearshealthcyber.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Lekarna\DoctrineExtensionsTree\DI;

use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;


class TreeExtension extends CompilerExtension implements IEntityProvider
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('listener'))
			->setClass('Gedmo\Tree\TreeListener')
			->addSetup('setAnnotationReader', array('@Doctrine\Common\Annotations\Reader'))
			->addTag(EventsExtension::TAG_SUBSCRIBER);
	}


	/**
	 * @return array
	 */
	public function getEntityMappings()
	{
		return array(
			'Gedmo\Tree\Entity' => $this->getGedmoTreeEntityPath()
		);
	}


	/**
	 * @return string
	 */
	private function getGedmoTreeEntityPath()
	{
		$relativePath = '/vendor/gedmo/doctrine-extensions/lib/Gedmo/Tree/Entity';
		if ($path = realpath(__DIR__  . '/../../../..' . $relativePath)) {
			return $path;
		}

		if ($path = realpath(__DIR__  . '/../../../../../../..' . $relativePath)) {
			return $path;
		}

		return FALSE;
	}

}
