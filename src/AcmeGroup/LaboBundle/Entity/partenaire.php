<?php

namespace AcmeGroup\LaboBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
// Slug
use Gedmo\Mapping\Annotation as Gedmo;
use labo\Bundle\TestmanuBundle\Entity\partenaire as partenaireBase;

/**
 * partenaire
 *
 * @ORM\Entity
 * @ORM\Table(name="partenaire")
 * @ORM\Entity(repositoryClass="AcmeGroup\LaboBundle\Entity\partenaireRepository")
 */
class partenaire extends partenaireBase {

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
     * @ORM\Column(name="subvention", type="integer")
     */
    private $subvention;

    /**
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\typePartenaire")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    protected $typePartenaire;


    public function __construct() {
    	PARENT::__construct();
        $this->subvention = 0;
    }

    /**
     * Set subvention
     *
     * @param string $subvention
     * @return partenaire
     */
    public function setSubvention($subvention = 0)
    {
        $this->subvention = intval($subvention);
    
        return $this;
    }

    /**
     * Get subvention
     *
     * @return string 
     */
    public function getSubvention()
    {
        return $this->subvention;
    }

    /**
     * Set typePartenaire
     *
     * @param \AcmeGroup\LaboBundle\Entity\typePartenaire $typePartenaire
     * @return partenaire
     */
    public function setTypePartenaire(\AcmeGroup\LaboBundle\Entity\typePartenaire $typePartenaire = null)
    {
        $this->typePartenaire = $typePartenaire;
    
        return $this;
    }

    /**
     * Get typePartenaire
     *
     * @return \AcmeGroup\LaboBundle\Entity\typePartenaire 
     */
    public function getTypePartenaire()
    {
        return $this->typePartenaire;
    }


}
