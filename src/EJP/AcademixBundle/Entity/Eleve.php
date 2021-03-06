<?php

namespace EJP\AcademixBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Repository\RepositoryFactory;
use EJP\AcademixBundle\Service\AnneeScolaire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Eleve
 *
 * @ORM\Table(name="eleve")
 * @ORM\Entity(repositoryClass="EJP\AcademixBundle\Repository\EleveRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Eleve extends Utilisateur
{

    /**
     * @ORM\PrePersist
     */

    public function setFirstEtude(){

        $etude = new Etude();
        $etude->setEleve($this);
        $etude->setClasse($this->currentClasse);
        $this->etudes->add($etude);

    }


    public function __construct()
    {
        parent::__construct();
        $this->parents = new ArrayCollection();
        $this->etudes = new ArrayCollection();

    }


    /**
     * @var Etude
     * One eleve has Many etudes.
     * @ORM\OneToMany(targetEntity="Etude", mappedBy="eleve",cascade={"persist"})
     */

    private $etudes;


    /**
     * @var Parents
     * @ORM\OneToMany(targetEntity="Parents", mappedBy="eleve",cascade={"persist"})
     */

    private $parents;

    /**
     * @var Etude
     *
     */

    private $currentEtude;

    private $currentClasse;

    private $bulletin;


    /**
     * @param mixed $bulletin
     */
    public function setBulletin(File $bulletin = null)
    {
        $etude = $this->getCurrentEtude();
        $etude->setMyFile($bulletin);
        $this->bulletin = $bulletin;
    }

    /**
     * @return mixed
     */
    public function getBulletin()
    {
        return $this->bulletin;
    }

    public function getCurrentClasse()
    {
        // L'ors de l'ajout d'un nouveau eleve, l'etude va être null
        if ($this->getCurrentEtude() != null) {

            $currentClasse = $this->getCurrentEtude()->getClasse();
            return $currentClasse;
        }
        return null;

    }

    /**
     * @param mixed $currentClasse
     */
    public function setCurrentClasse($currentClasse)
    {
        // L'ors de l'ajout d'un nouveau eleve, l'etude va être null
        if ($this->getCurrentEtude() != null){

        $etude = $this->getCurrentEtude();
        $etude->setClasse($currentClasse);
        $this->currentClasse = $currentClasse;

        }else{
            // sinon on la prépare pour la prepersistance
            $this->currentClasse = $currentClasse;

        }
    }

    /**
     * @return Etude
     */
    public function getCurrentEtude()
    {
        $year =  AnneeScolaire::getAnneeScolaire();
        $currentEtude = null;

        /** @var Etude $etude */
        foreach ($this->getEtudes() as $etude){
            if ($etude->getAnneeScolaire()==$year){
                $currentEtude=$etude;
            }
        }

        return $currentEtude;

        /*
        $etude = null;
        $i = 0;
        while($etude == null && $i<count($this->getEtudes())){
            if($this->getEtudes()[$i]->getAnneeScolaire()==$year){
                $etude=$this->getEtudes()[$i];
            }
            $i++;
        }

        return $etude;
        */

    }

    /**
     * @param mixed $currentEtude
     */
    public function setCurrentEtude($currentEtude)
    {

        $this->currentEtude = $currentEtude;
        $this->currentEtude->setEleve($this);
        $this->etudes->add($currentEtude);
        return $this;
    }






    /**
     * @return Etude
     */
    public function getEtudes()
    {
        return $this->etudes;
    }

    /**
     * @param mixed $etude
     */
    public function setEtudes($etudes)
    {
        if(!empty($etudes) && $etudes === $this->etudes) {
            reset($etudes);
            $etudes[key($etudes)] = clone current($etudes);
        }


        $this->etudes = $etudes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * @return mixed
     */
    public function getParentsResponsable()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("responsable", true));


        return $this->parents->matching($criteria);

    }
    /**
     * @param mixed $parents
     */
    public function setParents($parents)
    {
        if(!empty($parents) && $parents === $this->parents) {
            reset($parents);
            $parents[key($parents)] = clone current($parents);
        }
        $this->parents = $parents;
        return $this;
    }




    /**
     * Add etude
     *
     * @param \EJP\AcademixBundle\Entity\Etude $etude
     *
     * @return Eleve
     */
    public function addEtude(\EJP\AcademixBundle\Entity\Etude $etude)
    {
        // je l'est elevé pour voir si sa marche sans ou pas
        //$etude->setEleve($this);
        $this->etudes[] = $etude;

        return $this;
    }

    /**
     * Remove etude
     *
     * @param \EJP\AcademixBundle\Entity\Etude $etude
     */
    public function removeEtude(\EJP\AcademixBundle\Entity\Etude $etude)
    {
        $this->etudes->removeElement($etude);
    }

    /**
     * Add parent
     *
     * @param \EJP\AcademixBundle\Entity\Parents $parent
     *
     * @return Eleve
     */
    public function addParent(\EJP\AcademixBundle\Entity\Parents $parent)
    {
        $parent->setEleve($this);
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \EJP\AcademixBundle\Entity\Parents $parent
     */
    public function removeParent(\EJP\AcademixBundle\Entity\Parents $parent)
    {
        $this->parents->removeElement($parent);
    }
}
