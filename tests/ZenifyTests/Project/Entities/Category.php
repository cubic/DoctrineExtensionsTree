<?php

namespace ZenifyTests\Project\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @method string       getName()
 * @method string       getPath()
 * @method Category     getParent()
 * @method Category     setName()
 * @method Category     setPath()
 * @method Category     setParent()
 *
 * @ORM\Entity(repositoryClass="ZenifyTests\Project\Model\CategoryTree")
 * @Gedmo\Tree(type="materializedPath")
 */
class Category extends BaseEntity
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 */
	public $id;

	/**
	 * @Gedmo\TreePathSource
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $name;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Category", cascade={"persist"})
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=TRUE)
	 * @var Category
	 */
	protected $parent;

	/**
 	 * @Gedmo\TreePath(separator="|")
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $path;

}
