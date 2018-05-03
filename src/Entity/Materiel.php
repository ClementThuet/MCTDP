<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterielRepository")
 * @ORM\Table(name="materiel")
 */
class Materiel
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
    */
    private $categorie;
    
    
    /**
     * @ORM\Column(type="integer",nullable=true)
    */
    private $qteStock;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $numLot;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $description;
    
    /** 
     * @ORM\ManyToMany(targetEntity="App\Entity\Visite", mappedBy="materiels")
     * @ORM\JoinColumn(nullable=true)
    */
    private $visite;
   
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisationMaterielVisite", mappedBy="materiel")
     * @ORM\JoinColumn(nullable=true)
    */
    private $utilisationsMaterielVisite;
    
    public function __construct()
    {
        //$this->visites = new ArrayCollection();
    }
    
  
   function getId() {
       return $this->id;
   }

   function getNom() {
       return $this->nom;
   }

   function getCategorie() {
       return $this->categorie;
   }


   function setCategorie($categorie) {
       $this->categorie = $categorie;
   }

   function setId($id) {
       $this->id = $id;
   }

   function setNom($nom) {
       $this->nom = $nom;
   }
  
   function getNumLot() {
       return $this->numLot;
   }

   function getDescription() {
       return $this->description;
   }
   function getVisite() {
       return $this->visite;
   }

   function setNumLot($numLot) {
       $this->numLot = $numLot;
   }

   function setDescription($description) {
       $this->description = $description;
   }

   function setVisite(Visite $visite) {
       $this->visite = $visite;
   }

   function getQteStock() {
       return $this->qteStock;
   }

   function setQteStock($qteStock) {
       $this->qteStock = $qteStock;
   }
   public function addVisite(Visite $visite)
   {
        if (!$this->visite->contains($visite)) {
            $this->visite->add($visite);
        }
   }
   function getUtilisationMaterielVisite() {
       return $this->utilisationMaterielVisite;
   }

   function setUtilisationMaterielVisite($utilisationMaterielVisite) {
       $this->utilisationMaterielVisite = $utilisationMaterielVisite;
   }


   

   




}
