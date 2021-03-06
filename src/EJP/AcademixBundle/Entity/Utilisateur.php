<?php

namespace EJP\AcademixBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use EJP\AcademixBundle\Form\RemarqueType;
use EJP\AcademixBundle\Service\Generator;
use EJP\AcademixBundle\Service\Role;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * Utilisateur
 *
 * @ORM\Table(name="utilisateur")
 * @ORM\Entity(repositoryClass="EJP\AcademixBundle\Repository\UtilisateurRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\InheritanceType("JOINED")
 * @Vich\Uploadable
 */


class Utilisateur implements UserInterface, \Serializable
{

    /**
     * @ORM\PrePersist
     */

    public function setLoginGenerated(){

        //il n'est pas possible de le definir dans le constructeur car on a une variable qui vient du formulaire
        $this->setUsername(Generator::generateLogin($this));

    }

    /**
     * Utilisateur constructor.
     */
    public function __construct()
    {

        $this->createdAt = new \DateTime();
        $this->etat = true;
        $this->setRoles(Role::roleFinder($this));
        $this->setPassword(Generator::generatePassword());
        $this->remarques = new ArrayCollection();

    }


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=10)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime",nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var LastCon
     * One Utilisateur has Many lastcons.
     * @ORM\OneToMany(targetEntity="LastCon", mappedBy="utilisateur",cascade={"persist"})
     */

    private $lastcons;

    /**
     * @var Remarque
     * One Utilisateur has Many remqrques.
     * @ORM\OneToMany(targetEntity="Remarque", mappedBy="utilisateur",cascade={"persist"})
     */

    private $remarques;

    private $remarque;







    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_naissance", type="date")
     */
    private $dateNaissance;
    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="json_array", nullable= true)
     */
    private $roles = [];


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param Remarque $remarque
     */
    public function setRemarque($rq)
    {
        $remarque = new Remarque();
        $remarque->setLabel($rq);
        $remarque->setUtilisateur($this);
        $this->addRemarque($remarque);
        $this->remarque=$remarque;
    }

    /**
     * @return Remarque
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * @return Remarque
     */
    public function getRemarques()
    {
        return $this->remarques;
    }


    /**
     * @param Remarque $remarque
     */
    function addRemarque($remarque){
        $this->remarques->add($remarque);
    }

    /**
     * @param Remarque $remarque
     */
    function removeRemarque($remarque){
        $this->remarques->removeElement($remarque);
    }

    /**
     * @return LastCon
     */
    public function getLastcons()
    {
        return $this->lastcons;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }


    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }


    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }


    /**
     * Set etat
     *
     * @param bool $etat
     *
     * @return Utilisateur
     */
    public function setEtat($etat)
    {

        $this->etat = $etat;

        return $this;
    }

    /**
 * Get etat
 *
 * @return bool
 */
    public function getEtat()
    {
        return $this->etat;
    }



    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Utilisateur
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Utilisateur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Utilisateur
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * @param \DateTime $dateNaissance
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;
    }

    public function __toString()
    {
        return $this->getNom().' '.$this->getPrenom();
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }


    /**
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }



    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }



    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }



    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/jpg"}
     * )
     * @Vich\UploadableField(mapping="media_file", fileNameProperty="imageName")
     *
     * @var File
     */
    private $myFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $imageName;


    public function setMyFile(File $thefile = null)
    {
        $this->myFile = $thefile;

        if ($thefile instanceof UploadedFile) {
            $this->setUpdatedAt(new \DateTime());
        }


        return $this;
    }

    /**
     * @return File|null
     */
    public function getMyFile()
    {
        return $this->myFile;
    }

    /**
     * @param string $imageName
     *
     *
     */

    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @return string
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }







}

