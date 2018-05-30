<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @ORM\Table(name="categorie")
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="categorie", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
    */
    private $produits;
    
     /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Materiel", mappedBy="categorie", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
    */
    private $materiels;
   
    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }
    
    /**
     * @return Collection|Produit[]
    */
    public function getProduits()
    {
        return $this->produits;
    }
    
    public function addProduit(Produit $produit)
    {
        if ($this->produits->contains($produit)) {
            return;
        }

        $this->produits[] = $produit;
        // set the *owning* side!
        $produit->setCategorie($this);
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
   function getMateriels() {
       return $this->materiels;
   }

   function setMateriels($materiels) {
       $this->materiels = $materiels;
   }



}
