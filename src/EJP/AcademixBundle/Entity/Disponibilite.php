<?php

namespace EJP\AcademixBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Disponibilite
 *
 * @ORM\Table(name="disponibilite")
 * @ORM\Entity(repositoryClass="EJP\AcademixBundle\Repository\DisponibiliteRepository")
 */
class Disponibilite
{

    function __construct()
    {
        $this->horairesRDV = new ArrayCollection();
        $this->etat=true;
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
     * @ORM\Column(name="jour", type="string", length=20)
     */
    private $jour;

    /**
     * @var boolean
     *
     * @ORM\Column(name="etat", type="boolean")
     */
    private $etat;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Enseignant")
     * @ORM\JoinColumn(name="enseignant_id", referencedColumnName="id")
     */

    private $enseignant;


    /**
     * @var HoraireRDV
     * @ORM\OneToMany(targetEntity="HoraireRDV", mappedBy="disponibilite",cascade={"persist"})
     */

    private $horairesRDV;


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
     * Set jour
     *
     * @param string $jour
     *
     * @return Disponibilite
     */
    public function setJour($jour)
    {
        $this->jour = $jour;

        return $this;
    }

    /**
     * Get jour
     *
     * @return string
     */
    public function getJour()
    {
        return $this->jour;
    }

    /**
     * Set etat
     *
     * @param boolean $etat
     *
     * @return Disponibilite
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return boolean
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set enseignant
     *
     * @param \stdClass $enseignant
     *
     * @return Disponibilite
     */
    public function setEnseignant($enseignant)
    {
        $this->enseignant = $enseignant;

        return $this;
    }

    /**
     * Get enseignant
     *
     * @return \stdClass
     */
    public function getEnseignant()
    {
        return $this->enseignant;
    }

    /**
     * Add horairesRDV
     *
     * @param \EJP\AcademixBundle\Entity\HoraireRDV $horairesRDV
     *
     * @return Disponibilite
     */
    public function addHorairesRDV(HoraireRDV $horairesRDV)
    {

        $horairesRDV->setDisponibilite($this);
        $this->horairesRDV[] = $horairesRDV;

        return $this;
    }

    /**
     * Remove horairesRDV
     *
     * @param \EJP\AcademixBundle\Entity\HoraireRDV $horairesRDV
     */
    public function removeHorairesRDV(HoraireRDV $horairesRDV)
    {
        $this->horairesRDV->removeElement($horairesRDV);
    }

    /**
     * Get horairesRDV
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHorairesRDV()
    {
        return $this->horairesRDV;
    }

    /**
     * @param Parents $horairesRDV
     */
    public function setHorairesRDV($horairesRDV)
    {
        $this->horairesRDV = $horairesRDV;
    }

}
