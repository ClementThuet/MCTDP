<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UtilisationMaterielVisiteRepository")
 * @ORM\Table(name="utilisationMaterielVisite")
 */
class UtilisationMaterielVisite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
    */
    private $quantite;
    
    /**
     * @ORM\Column(type="string")
    */
    private $numLot;    

    /** 
     * @ORM\ManyToOne(targetEntity="App\Entity\Visite", inversedBy="utilisationsMaterielVisite")
     * @ORM\JoinColumn(nullable=false)
    */
    private $visite;
   
    /** 
     * @ORM\ManyToOne(targetEntity="App\Entity\Materiel", inversedBy="utilisationsMaterielVisite")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $materiel;
    
   
    function getId() {
        return $this->id;
    }

    function getQuantite() {
        return $this->quantite;
    }

    function getVisite() {
        return $this->visite;
    }

    function getMateriel() {
        return $this->materiel;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setQuantite($quantite) {
        $this->quantite = $quantite;
    }

    function setVisite($visite) {
        $this->visite = $visite;
    }

    function setMateriel($materiel) {
        $this->materiel = $materiel;
    }
    
    function getNumLot() {
        return $this->numLot;
    }

    function setNumLot($numLot) {
        $this->numLot = $numLot;
    }







   




}
