<?php

namespace App\Entity;
use Doctrine\ORM\Mapping\JoinTable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Visite", inversedBy="materiels")
     * @ORM\JoinColumn(nullable=true)
    * @JoinTable(name="visites_materiels")
    */
    private $visites;
   
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

   function getVisites() {
       return $this->visites;
   }

   function setNumLot($numLot) {
       $this->numLot = $numLot;
   }

   function setDescription($description) {
       $this->description = $description;
   }

   function setVisites($visites) {
       $this->visites = $visites;
   }

   function getQteStock() {
       return $this->qteStock;
   }

   function setQteStock($qteStock) {
       $this->qteStock = $qteStock;
   }




   




}
