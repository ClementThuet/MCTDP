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
     * @ORM\ManyToOne(targetEntity="App\Entity\Visite", inversedBy="prescription")
    */
    private $visite;
    
    /** 
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit", inversedBy="prescription")
    *  @ORM\JoinTable(name="produits_prescription",
     *      joinColumns={@JoinColumn(name="prescription_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="produit_id", referencedColumnName="id")}
     *      )
     * @ORM\JoinColumn(onDelete="CASCADE")
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
    
    /**
     * @param Produit $produit
     */
    public function addProduit(Produit $produit)
    {
        if ($this->produits->contains($produit)) {
            return;
        }

        $this->produits->add($produit);
        //$produit->addPrescription($this);
    }
    
    public function removeProduit(Produit $produit)
    {
        if (!$this->produits->contains($produit)) {
            return;
        }
        $this->produits->removeElement($produit);
        //$produit->removePrescription($this);
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
