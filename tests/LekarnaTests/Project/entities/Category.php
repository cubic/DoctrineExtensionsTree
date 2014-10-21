<?php

namespace Project\Entities;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @method string       getName()
 * @method string       getPath()
 * @method Category     getParent()
 * @method Category[]   getChildren()
 * @method Category     setName()
 * @method Category     setPath()
 * @method Category     setParent()
 * @method Category     setChildren()
 *
 * @ORM\Entity(repositoryClass="Project\Model\CategoryTree")
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
	 * @ORM\ManyToOne(targetEntity="Category", inversedBy="children", cascade={"persist"})
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=TRUE)
	 * @var Category
	 */
	protected $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Category", mappedBy="parent", cascade={"persist"})
	 * @var Category
	 */
	protected $children;

	/**
 	 * @Gedmo\TreePath(separator="|")
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $path;


	public function __construct()
	{
		$this->children = new ArrayCollection;
	}

}
