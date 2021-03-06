<?php
/**
 * Created by PhpStorm.
 * User: SaddeM
 * Date: 21/11/2017
 * Time: 18:30
 */

namespace EJP\AcademixBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use EJP\AcademixBundle\Entity\Evenement;

class EvenementFixtures extends Fixture
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 5; $i++) {
            $evenement = new Evenement();
            $evenement->setTitre('TitreEve '.$i);
            $evenement->setDescription("DescEven ".$i);
            $evenement->setDateEvenement(new \DateTime());
            $evenement->setHeureDebut("08");
            $evenement->setHeureFin("12");
            $evenement->setDatePublication(new \DateTime());
            $evenement->setEtat(1);

            $manager->persist($evenement);
        }

        $manager->flush();
    }
}