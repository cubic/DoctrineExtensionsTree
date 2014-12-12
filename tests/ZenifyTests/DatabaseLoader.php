<?php

namespace ZenifyTests;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Nette;
use ZenifyTests\Project\Entities\Category;


class DatabaseLoader
{

	/**
	 * @var bool
	 */
	private $isDbSchemaPrepared = FALSE;

	/**
	 * @var Connection
	 */
	private $connection;

	/**
	 * @var EntityManager
	 */
	private $entityManager;


	public function __construct(Connection $connection, EntityManager $entityManager)
	{
		$this->connection = $connection;
		$this->entityManager = $entityManager;
	}


	public function prepareCategoryTableWithTwoItems()
	{
		if ( ! $this->isDbSchemaPrepared) {
			/** @var Connection $connection */
			$this->connection->query('CREATE TABLE category (id INTEGER NOT NULL, parent_id int NULL,'
				. 'path string, name string, PRIMARY KEY(id))');

			$fruitCategory = new Category;
			$fruitCategory->setName('Fruit');

			$appleCategory = new Category;
			$appleCategory->setName('Apple');
			$appleCategory->setParent($fruitCategory);

			$this->entityManager->persist($fruitCategory);
			$this->entityManager->persist($appleCategory);
			$this->entityManager->flush();
			$this->isDbSchemaPrepared = TRUE;
		}
	}

}
