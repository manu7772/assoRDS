<?php
// src/AcmeGroup/LaboBundle/Entity/membre.php
 
namespace AcmeGroup\LaboBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="membre")
 * @ORM\Entity(repositoryClass="AcmeGroup\LaboBundle\Entity\membreRepository")
 * @UniqueEntity(fields={"email"}, message="Ce membre (mail) existe déjà.")
 */
class membre {
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="AcmeGroup\UserBundle\Entity\User", mappedBy="membre")
     * @ORM\JoinColumn(nullable=true, unique=true)
     */
    private $User;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="nom", type="string", length=100, nullable=true, unique=false)
	 */
	private $nom;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="email", type="string", length=100, nullable=true, unique=false)
	 */
	private $email;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="prenom", type="string", length=100, nullable=true, unique=false)
	 */
	private $prenom;

    /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="AcmeGroup\LaboBundle\Entity\image")
     * @ORM\JoinColumn(nullable=true, unique=false)
     */
    private $avatar;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="AcmeGroup\LaboBundle\Entity\typeMembre")
     * @ORM\JoinColumn(nullable=false, unique=false)
     */
    private $typeMembre;

	/**
	* @ORM\Column(name="preferences", type="array", nullable=true)
	*/
	private $preferences;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="adresse", type="text", nullable=true, unique=false)
	 */
	private $adresse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="cp", type="string", length=10, nullable=true, unique=false)
	 */
	private $cp;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="ville", type="string", length=255, nullable=true, unique=false)
	 */
	private $ville;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="commentaire", type="text", nullable=true, unique=false)
	 */
	private $commentaire;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="annotation", type="text", nullable=true, unique=false)
	 */
	private $annotation;

	/**
	* @var string
	*
	* @ORM\Column(name="adressIp", type="array", nullable=true)
	*/
	private $adressIp;

	/**
	* @var string
	*
	* @ORM\Column(name="autresMails", type="array", nullable=true)
	*/
	private $autresMails;

	/**
	* @var string
	*
	* @ORM\Column(name="tel", type="string", length=15, nullable=true)
	*/
	private $tel;

	/**
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\image", mappedBy="propUser")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $images;

	/**
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\video", mappedBy="propUser")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $videos;

	/**
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\evenement", mappedBy="propUser")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $evenements;

	/**
	 * @ORM\OneToMany(targetEntity="AcmeGroup\LaboBundle\Entity\fichierPdf", mappedBy="propUser")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $fichierPdfs;

	/**
	 * @var array
	 *
	 * @ORM\ManyToMany(targetEntity="AcmeGroup\LaboBundle\Entity\version")
	 */
	private $versions;



	public function __construct() {
		$this->User = null;
		$this->images = new ArrayCollection();
		$this->videos = new ArrayCollection();
		$this->evenements = new ArrayCollection();
		$this->fichierPdfs = new ArrayCollection();
		$this->versions = new ArrayCollection();
	}

    /**
     * Set User
     *
     * @param \AcmeGroup\UserBundle\Entity\User $User
     * @return membre
     */
    public function setUser(\AcmeGroup\UserBundle\Entity\User $User = null)
    {
        $this->User = $User;
    	$User->setMembreInverse($this);
        return $this;
    }

    /**
     * Set setUserInverse
     *
     * @param \AcmeGroup\UserBundle\Entity\User $User
     * @return membre
     */
    public function setUserInverse(\AcmeGroup\UserBundle\Entity\User $User)
    {
        $this->User = $User;
        return $this;
    }

    /**
     * Get User
     *
     * @return \AcmeGroup\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->User;
    }

	// ADRESSE
	public function isAdressePartielle() {
		// TRUE si au moins un des champs d'adresse est renseigné
		if(($this->livraison === "poste") && ($this->nom !== null || $this->prenom !== null || $this->adresse !== null || $this->cp !== null || $this->ville !== null || $this->commentaire !== null || $this->tel !== null)) {
			return true;
		} else return false;
	}

	public function isAdresseNulle() {
		// TRUE si aucun des champs d'adresse n'est renseigné
		if($this->nom === null && $this->prenom === null && $this->adresse === null && $this->cp === null && $this->ville === null && $this->commentaire === null && $this->tel === null) {
			return true;
		} else return false;
	}

	public function isAdresseComplete() {
		// TRUE si les champs obligatoires mini sont renseignés (adresse valide)
		if($this->nom !== null && $this->adresse !== null && $this->cp !== null && $this->ville !== null) {
			return true;
		} else return false;
	}

	public function isAdresseNotComplete() {
		// TRUE si au moins un des champs obligatoires n'est pas renseigné
		if(($this->livraison === "poste") && ($this->nom === null || $this->adresse === null || $this->cp === null || $this->ville === null)) {
			return true;
		} else return false;
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Add preference
	 *
	 * @param $preferences
	 * @return User
	 */
	public function addPreference($preferences) {
		$this->preferences[] = $preferences;
		return $this;
	}

	/**
	 * Remove preference
	 *
	 * @param $key
	 */
	public function removePreference($preferences) {
		$this->preferences->removeElement($preferences);
	}

	/**
	 * Get preferences
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getPreferences() {
		return $this->preferences;
	}

	/**
	 * Set adressIp
	 *
	 * @param array $adressIp
	 * @return User
	 */
	public function setAdressIp($adressIp) {
		$this->adressIp = $adressIp;
	
		return $this;
	}

	/**
	 * Get adressIp
	 *
	 * @return array 
	 */
	public function getAdressIp() {
		return $this->adressIp;
	}

	/**
	 * Set tel
	 *
	 * @param string $tel
	 * @return User
	 */
	public function setTel($tel) {
		$this->tel = $tel;
	
		return $this;
	}

	/**
	 * Get tel
	 *
	 * @return string 
	 */
	public function getTel() {
		return $this->tel;
	}

	/**
	 * Set autresTels
	 *
	 * @param array $autresTels
	 * @return User
	 */
	public function setAutresTels($autresTels) {
		$this->autresTels = $autresTels;
	
		return $this;
	}

	/**
	 * Get autresTels
	 *
	 * @return array 
	 */
	public function getAutresTels() {
		return $this->autresTels;
	}

	/**
	 * Add images
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $images
	 * @return User
	 */
	public function addImage(\AcmeGroup\LaboBundle\Entity\image $images) {
		$this->images[] = $images;
		return $this;
	}

	/**
	 * Remove images
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\image $images
	 */
	public function removeImage(\AcmeGroup\LaboBundle\Entity\image $images) {
		$this->images->removeElement($images);
		$images->setPropUser(null); // relation facultative !!! (nullable = true)
	}

	/**
	 * Get images
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getImages() {
		return $this->images;
	}

	/**
	 * Add videos
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\video $videos
	 * @return User
	 */
	public function addVideo(\AcmeGroup\LaboBundle\Entity\video $videos) {
		$this->videos[] = $videos;
		$videos->setPropUser($this); // ajout pour relation bidirectionnelle
		return $this;
	}

	/**
	 * Remove videos
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\video $videos
	 */
	public function removeVideo(\AcmeGroup\LaboBundle\Entity\video $videos) {
		$this->videos->removeElement($videos);
		$videos->setPropUser(null); // relation facultative !!! (nullable = true)
	}

	/**
	 * Get videos
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVideos() {
		return $this->videos;
	}

	/**
	 * Add evenements
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\evenement $evenements
	 * @return User
	 */
	public function addEvenement(\AcmeGroup\LaboBundle\Entity\evenement $evenements) {
		$this->evenements[] = $evenements;
		$evenements->setPropUser($this); // ajout pour relation bidirectionnelle
		return $this;
	}

	/**
	 * Remove evenements
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\evenement $evenements
	 */
	public function removeEvenement(\AcmeGroup\LaboBundle\Entity\evenement $evenements) {
		$this->evenements->removeElement($evenements);
		$evenements->setPropUser(null); // relation facultative !!! (nullable = true)
	}

	/**
	 * Get evenements
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getEvenements() {
		return $this->evenements;
	}

	/**
	 * Set adresse
	 *
	 * @param string $adresse
	 * @return User
	 */
	public function setAdresse($adresse) {
		$this->adresse = $adresse;
	
		return $this;
	}

	/**
	 * Get adresse
	 *
	 * @return string 
	 */
	public function getAdresse() {
		return $this->adresse;
	}

	/**
	 * Set nom
	 *
	 * @param string $nom
	 * @return User
	 */
	public function setNom($nom) {
		$this->nom = $nom;
	
		return $this;
	}

	/**
	 * Get nom
	 *
	 * @return string 
	 */
	public function getNom() {
		return $this->nom;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return User
	 */
	public function setEmail($email) {
		$this->email = $email;
	
		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string 
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set prenom
	 *
	 * @param string $prenom
	 * @return User
	 */
	public function setPrenom($prenom) {
		$this->prenom = $prenom;
	
		return $this;
	}

	/**
	 * Get prenom
	 *
	 * @return string 
	 */
	public function getPrenom() {
		return $this->prenom;
	}

	/**
	 * Set cp
	 *
	 * @param string $cp
	 * @return User
	 */
	public function setCp($cp) {
		$this->cp = $cp;
	
		return $this;
	}

	/**
	 * Get cp
	 *
	 * @return string 
	 */
	public function getCp() {
		return $this->cp;
	}

	/**
	 * Set ville
	 *
	 * @param string $ville
	 * @return User
	 */
	public function setVille($ville) {
		$this->ville = $ville;
	
		return $this;
	}

	/**
	 * Get ville
	 *
	 * @return string 
	 */
	public function getVille() {
		return $this->ville;
	}

	/**
	 * Set commentaire
	 *
	 * @param string $commentaire
	 * @return User
	 */
	public function setCommentaire($commentaire) {
		$this->commentaire = $commentaire;
	
		return $this;
	}

	/**
	 * Get commentaire
	 *
	 * @return string 
	 */
	public function getCommentaire() {
		return $this->commentaire;
	}

	/**
	 * Get annotation
	 *
	 * @return string
	 */
	public function getAnnotation() {
		return $this->annotation;
	}

	/**
	 * Set annotation
	 *
	 * @param string $annotation
	 * @return User
	 */
	public function setAnnotation($annotation) {
		$this->annotation = $annotation;
	
		return $this;
	}


	/**
	 * Add fichierPdfs
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdfs
	 * @return User
	 */
	public function addFichierPdf(\AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdfs) {
		$this->fichierPdfs[] = $fichierPdfs;
		$fichierPdfs->setPropUser($this); // ajout pour relation bidirectionnelle
		return $this;
	}

	/**
	 * Remove fichierPdfs
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdfs
	 */
	public function removeFichierPdf(\AcmeGroup\LaboBundle\Entity\fichierPdf $fichierPdfs) {
		$this->fichierPdfs->removeElement($fichierPdfs);
		$fichierPdfs->setPropUser(null); // relation facultative !!! (nullable = true)
	}

	/**
	 * Get fichierPdfs
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getFichierPdfs() {
		return $this->fichierPdfs;
	}

	/**
	 * Add versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 * @return User
	 */
	public function addVersion(\AcmeGroup\LaboBundle\Entity\version $versions)
	{
		$this->versions[] = $versions;
	
		return $this;
	}

	/**
	 * Remove versions
	 *
	 * @param \AcmeGroup\LaboBundle\Entity\version $versions
	 */
	public function removeVersion(\AcmeGroup\LaboBundle\Entity\version $versions)
	{
		$this->versions->removeElement($versions);
	}

	/**
	 * Get versions
	 *
	 * @return \Doctrine\Common\Collections\Collection 
	 */
	public function getVersions()
	{
		return $this->versions;
	}

    /**
     * Set avatar
     *
     * @param \AcmeGroup\LaboBundle\Entity\image $avatar
     * @return User
     */
    public function setAvatar(\AcmeGroup\LaboBundle\Entity\image $avatar)
    {
        $this->avatar = $avatar;
    
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AcmeGroup\LaboBundle\Entity\image 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set typeMembre
     *
     * @param \AcmeGroup\LaboBundle\Entity\typeMembre $typeMembre
     * @return User
     */
    public function setTypeMembre(\AcmeGroup\LaboBundle\Entity\typeMembre $typeMembre)
    {
        $this->typeMembre = $typeMembre;
    
        return $this;
    }

    /**
     * Get typeMembre
     *
     * @return \AcmeGroup\LaboBundle\Entity\typeMembre 
     */
    public function getTypeMembre()
    {
        return $this->typeMembre;
    }


}