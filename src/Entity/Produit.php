<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="produit")
 */
class Produit
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
     * @ORM\ManyToOne(targetEntity="App\Entity\CategorieProduit", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
    */
    private $categorie;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $posologie;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $fonction;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Prescription", inversedBy="produits")
     * @ORM\JoinColumn(nullable=true)
    */
    private $prescription;
   
   function getId() {
       return $this->id;
   }

   function getNom() {
       return $this->nom;
   }

   function getCategorie() {
       return $this->categorie;
   }

   function getPosologie() {
       return $this->posologie;
   }

   function getFonction() {
       return $this->fonction;
   }

   function setCategorie($categorie) {
       $this->categorie = $categorie;
   }

   function setPosologie($posologie) {
       $this->posologie = $posologie;
   }

   function setFonction($fonction) {
       $this->fonction = $fonction;
   }

   
   function setId($id) {
       $this->id = $id;
   }

   function setNom($nom) {
       $this->nom = $nom;
   }
   function getPrescription() {
       return $this->prescription;
   }

   function setPrescription(Prescription $prescription) {
       $this->prescription = $prescription;
   }



   




}
