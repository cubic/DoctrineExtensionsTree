<?php

namespace ZenifyTests\DoctrineExtensionsTree;

use Doctrine\ORM\EntityManager;
use Gedmo\Tree\Entity\Repository\MaterializedPathRepository;
use Gedmo\Tree\TreeListener;
use Nette;
use PHPUnit_Framework_TestCase;
use ZenifyTests\ContainerFactory;
use ZenifyTests\DatabaseLoader;
use ZenifyTests\Project\Entities\Category;


class TreeTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	/**
	 * @var MaterializedPathRepository
	 */
	private $categoryRepository;


	public function __construct()
	{
		$this->container = (new ContainerFactory)->create();
	}


	protected function setUp()
	{
		/** @var EntityManager $entityManager */
		$entityManager = $this->container->getByType(EntityManager::class);
		$this->categoryRepository = $entityManager->getRepository(Category::class);

		/** @var DatabaseLoader $databaseLoader */
		$databaseLoader = $this->container->getByType(DatabaseLoader::class);
		$databaseLoader->prepareCategoryTableWithTwoItems();
	}


	public function testInstance()
	{
		$this->assertInstanceOf(
			TreeListener::class,
			$this->container->getByType(TreeListener::class)
		);
	}


	public function testParent()
	{
		/** @var Category $category */
		$category = $this->categoryRepository->find(2);
		$this->assertInstanceOf(Category::class, $category);
		$this->assertSame('Apple', $category->getName());

		$this->assertInstanceOf(Category::class, $category->getParent());
		$this->assertSame('Fruit', $category->getParent()->getName());
	}


	public function testPath()
	{
		/** @var Category $category */
		$category = $this->categoryRepository->find(1);
		$this->assertSame('Fruit-1|', $category->getPath());

		/** @var Category $category */
		$category = $this->categoryRepository->find(2);
		$this->assertSame('Fruit-1|Apple-2|', $category->getPath());
	}


	public function testTreeRepository()
	{
		$category = $this->categoryRepository->find(1);
		/** @var Category[] $children */
		$children = $this->categoryRepository->getChildren($category);
		$this->assertCount(1, $children);
		$this->assertSame('Apple', $children[0]->getName());
	}

}
