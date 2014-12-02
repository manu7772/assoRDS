<?php

namespace AcmeGroup\LaboBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
use labo\Bundle\TestmanuBundle\Entity\version as versionBase;

/**
 * version
 *
 * @ORM\Entity
 * @ORM\Table(name="version")
 * @ORM\Entity(repositoryClass="AcmeGroup\LaboBundle\Entity\versionRepository")
 */
class version extends versionBase {

	/**
	 * @var integer
	 *
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $imageEnteteWide;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $imageEnteteMedi;

	/**
	 * @var integer
	 *
	 * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
	 * @ORM\JoinColumn(nullable=true, unique=false)
	 */
	private $imageEnteteMini;

	/**
	 * Set imageEnteteWide
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $imageEnteteWide
	 * @return version
	 */
	public function setImageEnteteWide(\AcmeGroup\LaboBundle\Entity\image $imageEnteteWide = null) {
		$this->imageEnteteWide = $imageEnteteWide;
		if($this->imageEnteteMedi === null) $this->imageEnteteMedi = $this->imageEnteteWide;
		if($this->imageEnteteMini === null) $this->imageEnteteMini = $this->imageEnteteWide;
		return $this;
	}

	/**
	 * Get imageEnteteWide
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getImageEnteteWide() {
		return $this->imageEnteteWide;
	}

	/**
	 * Set imageEnteteMedi
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $imageEnteteMedi
	 * @return version
	 */
	public function setImageEnteteMedi(\AcmeGroup\LaboBundle\Entity\image $imageEnteteMedi = null) {
		if($imageEnteteMedi === null) {
			$this->imageEnteteMedi = $this->imageEnteteWide;
			if($this->imageEnteteMini === null) $this->imageEnteteMini = $this->imageEnteteMedi;
		} else {
			$this->imageEnteteMedi = $imageEnteteMedi;
			if($this->imageEnteteMini === null) $this->imageEnteteMini = $this->imageEnteteMedi;
		}
		return $this;
	}

	/**
	 * Get imageEnteteMedi
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getImageEnteteMedi() {
		return $this->imageEnteteMedi;
	}

	/**
	 * Set imageEnteteMini
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $imageEnteteMini
	 * @return version
	 */
	public function setImageEnteteMini(\AcmeGroup\LaboBundle\Entity\image $imageEnteteMini = null) {
		if($imageEnteteMini === null) {
			$this->imageEnteteMini = $this->imageEnteteMedi;
			if($this->imageEnteteMini === null) $this->imageEnteteMini = $this->imageEnteteWide;
		} else $this->imageEnteteMini = $imageEnteteMini;
	
		return $this;
	}

	/**
	 * Get imageEnteteMini
	 *
	 * @return \AcmeGroup\LaboBundle\Entity\image 
	 */
	public function getImageEnteteMini() {
		return $this->imageEnteteMini;
	}


}