<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="prescription")
 */
class Prescription
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
     * @ORM\Column(type="date")
    */
    private $date;
    
    /**
     * @ORM\Column(type="text",nullable=true)
    */
    private $observations;
    
    
    /** 
     * @ORM\OneToOne(targetEntity="App\Entity\Visite", mappedBy="prescription")
     * @JoinColumn(name="visite_id", referencedColumnName="id")
    */
    private $visite;
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="prescription")
    */
    private $produits;
    
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
        $produit->setPrescription($this);
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getObservations() {
        return $this->observations;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setObservations($observations) {
        $this->observations = $observations;
    }

    public function getVisite()
    {
        return $this->visite;
    }
    
    function setVisite($visites) {
        $this->visite = $visites;
    }

        

    function setDate($date) {
        $this->date = $date;
    }
    function getDate() {
        return $this->date;
    }










}
