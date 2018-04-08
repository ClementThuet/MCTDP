<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="specialite")
 */
class Specialite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="string")
    */
    private $nom;
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Medecin", mappedBy="specialite", cascade={"persist", "remove"})
    */
    private $medecins;
   
    public function __construct()
    {
        $this->medecins = new ArrayCollection();
    }
    
    /**
     * @return Collection|Medecin[]
    */
    public function getMedecins()
    {
        return $this->medecins;
    }
    
    public function addMedecin(Medecin $medecin)
    {
        if ($this->medecins->contains($medecin)) {
            return;
        }

        $this->medecins[] = $medecin;
        // set the *owning* side!
        $medecin->setSpecialite($this);
    }
   function getId() {
       return $this->id;
   }

   function getNom() {
       return $this->nom;
   }
   
   function setId($id) {
       $this->id = $id;
   }

   function setNom($nom) {
       $this->nom = $nom;
   }


}
