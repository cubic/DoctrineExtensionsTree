<?php

namespace LekarnaTests\DoctrineExtensionsTree;

use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;
use Kdyby\Doctrine\Connection;
use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Nette;
use Project\Entities\Category;
use Project\Model\CategoryTree;
use Tester\Assert;
use Tester\TestCase;


$container = require __DIR__ . '/../bootstrap.php';


class TreeTest extends TestCase
{

	/**
	 * @var bool
	 */
	private $isDbPrepared = FALSE;

	/**
	 * @var MaterializedPathRepository
	 */
	private $categoryDao;

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var EntityManager
	 */
	private $em;


	public function __construct(Nette\DI\Container $container)
	{
		$this->container = $container;
	}


	protected function setUp()
	{
		$this->em = $this->container->getByType('Kdyby\Doctrine\EntityManager');
		$this->categoryDao = $this->em->getDao(Category::getClassName());
		$this->prepareDbData();
	}


	public function testInstance()
	{
		Assert::type(
			'Gedmo\Tree\TreeListener',
			$this->container->getByType('Gedmo\Tree\TreeListener')
		);
	}


	public function testParent()
	{
		/** @var Category $category */
		$category = $this->categoryDao->find(2);
		Assert::type(
			'Project\Entities\Category',
			$category
		);
		Assert::same('Apple', $category->getName());

		Assert::type(
			'Project\Entities\Category',
			$category->getParent()
		);
		Assert::same('Fruit', $category->getParent()->getName());
	}


	public function testPath()
	{
		/** @var Category $category */
		$category = $this->categoryDao->find(1);
		Assert::same('Fruit-1|', $category->getPath());

		/** @var Category $category */
		$category = $this->categoryDao->find(2);
		Assert::same('Fruit-1|Apple-2|', $category->getPath());
	}


	public function testTreeRepository()
	{
		$category = $this->categoryDao->find(1);
		/** @var Category[] $children */
		$children = $this->categoryDao->getChildren($category);
		Assert::count(1, $children);
		Assert::same(
			'Apple',
			$children[0]->getName()
		);
	}


	private function prepareDbData()
	{
		if ($this->isDbPrepared) {
			return;
		}

		/** @var Connection $connection */
		$connection = $this->container->getByType('Doctrine\DBAL\Connection');
		$connection->query('CREATE TABLE category (id INTEGER NOT NULL, parent_id int NULL,'
			. 'path string, name string, PRIMARY KEY(id))');

		$fruitCategory = new Category;
		$fruitCategory->setName('Fruit');

		$appleCategory = new Category;
		$appleCategory->setName('Apple');
		$appleCategory->setParent($fruitCategory);

		$this->em->persist($fruitCategory);
		$this->em->persist($appleCategory);
		$this->em->flush();
		$this->isDbPrepared = TRUE;
	}

}


\run(new TreeTest($container));
