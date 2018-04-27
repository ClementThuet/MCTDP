<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProduitRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="produits")
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
     * @ORM\Column(type="boolean",nullable=true)
    */
    private $obsolete;
    
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Prescription", mappedBy="produits")
     * @ORM\JoinColumn(nullable=true)
    */
    private $prescription;
   
    public function __construct()
    {
        $this->prescription = new ArrayCollection();
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

   function getObsolete() {
       return $this->obsolete;
   }

   function setObsolete($obsolete) {
       $this->obsolete = $obsolete;
   }



   




}
