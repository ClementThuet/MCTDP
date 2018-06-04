<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 * @ORM\Table(name="patient")
 */
class Patient {
   /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $nom;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $prenom;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $nomsAffichage;
    
    /**
    * @ORM\Column(name="dateNaiss",type="date", nullable=true)
    * @Assert\Date()
    */
    private $dateNaiss ;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresse;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $CP;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $telephone;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $mail;
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $sitFam;
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $profession;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $nbEnfant;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $accouchement;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $typeHabitat;

    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $allergies;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $traitementEnCours;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $atcdChirurgical;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $atcdFamiliaux;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $atcdMedical;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $contraception;
    
    
    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $observations;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepteMedNonTradi;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $accepteAcup;
    
    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\SeanceQG", mappedBy="patient", cascade={"persist", "remove"})
    */
    private $seancesQG;
    
   /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Visite", mappedBy="patient", cascade={"persist", "remove"})
    */
    private $visites;
    
    /**
     * @ManyToMany(targetEntity="App\Entity\Medecin", inversedBy="patients" )
     * @JoinTable(name="patients_medecins")
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $medecins;
    
    public function __construct()
    {
        $this->seancesQG = new ArrayCollection();
        $this->visites = new ArrayCollection();
        $this->medecins = new ArrayCollection();
        $this->couponsQiGong = new ArrayCollection();
        $this->documents = new ArrayCollection();
    }
    
    /**
     * @return Collection|SeanceQG[]
    */
    public function getSeancesQG()
    {
        return $this->seancesQG;
    }
    
    public function addSeanceQG(SeanceQG $seancesQG)
    {
        if ($this->seancesQG->contains($seancesQG)) {
            return;
        }

        $this->seancesQG[] = $seancesQG;
        // set the *owning* side!
        $seancesQG->setPatient($this);
    }
    
    public function removeSeanceQG(SeanceQG $seancesQG)
    {
        $this->seancesQG->removeElement($seancesQG);
    }
    /**
     * @return Collection|Visite[]
    */
    public function getVisites()
    {
        return $this->visites;
    }
    
    public function addVisite(Visite $visite)
    {
        if ($this->visites->contains($visite)) {
            return;
        }

        $this->visites[] = $visite;
        // set the *owning* side!
        $visite->setPatient($this);
    }
    
    /**
     * @return Collection|Medecin[]
    */
    function getMedecins() {
        return $this->medecins;
    }
    
    public function addMedecin(Medecin $medecin)
    {
        if ($this->medecins->contains($medecin)) {
            return;
        }

        $this->medecins[] = $medecin;
        // set the *owning* side!
        $medecin->setPatient($this);
    }
    
    public function removeMedecin(Medecin $medecin)
    {
        $this->medecins->removeElement($medecin);
    }
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\CouponQiGong", mappedBy="patient")
    */
    private $couponsQiGong;
    /**
     * @return Collection|CouponQiGong[]
    */
    public function getCouponsQiGong()
    {
        return $this->couponsQiGong;
    }
    
    public function addCouponsQiGong(Visite $couponsQiGong)
    {
        if ($this->visites->contains($couponsQiGong)) {
            return;
        }

        $this->couponsQiGong[] = $couponsQiGong;
        // set the *owning* side!
        $couponsQiGong->setPatient($this);
    }
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Document", mappedBy="patient")
    */
    private $documents;
    
    /*
     * @return Collection|Document[]
    */
    public function getDocuments()
    {
        return $this->documents;
    }
    
    public function addDocument(Document $document)
    {
        if ($this->documents->contains($document)) {
            return;
        }

        $this->documents[] = $document;
        // set the *owning* side!
        $document->setPatient($this);
    }
    
    public function getDatesPresence() 
    {
        return $this->datesPresenceQG;
    }
    
    public function addDatePresenceQG($datePresenceQG)
    {
       /* if ($this->datesPresenceQG->contains($datePresenceQG)) {
            return;
        }*/

        $this->datesPresenceQG[] = $datePresenceQG;
        // set the *owning* side!
        $datePresenceQG->setDatePresenceQG($this);
    }
    
    function setDatePresenceQG($datesPresenceQG) {
        $this->datesPresenceQG = $datesPresenceQG;
    }

        function setId($id) {
        $this->id = $id;
    }

    function getId() {
        return $this->id;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }
    
    function getDateNaiss() {
        return $this->dateNaiss;
    }

    function getAdresse() {
        return $this->adresse;
    }

    function getCP() {
        return $this->CP;
    }

    function getVille() {
        return $this->ville;
    }

    function getTelephone() {
        return $this->telephone;
    }

    function getMail() {
        return $this->mail;
    }

    function getSitFam() {
        return $this->sitFam;
    }

    function getProfession() {
        return $this->profession;
    }

    function getNbEnfant() {
        return $this->nbEnfant;
    }

    function getAccouchement() {
        return $this->accouchement;
    }

    function getTypeHabitat() {
        return $this->typeHabitat;
    }

    function getAllergies() {
        return $this->allergies;
    }

    function getTraitementEnCours(){
        return $this->traitementEnCours;
    }

    function getAtcdChirurgical() {
        return $this->atcdChirurgical;
    }

    function getAtcdFamiliaux() {
        return $this->atcdFamiliaux;
    }

    function getAtcdMedical() {
        return $this->atcdMedical;
    }

    function getContraception() {
        return $this->contraception;
    }

    function getObservations(){
        return $this->observations;
    }

    function getAccepteMedNonTradi() {
        return $this->accepteMedNonTradi;
    }

    function getAccepteAcup() {
        return $this->accepteAcup;
    }
    
    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    function setDateNaiss($dateNaiss) {
        $this->dateNaiss = $dateNaiss;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    function setCP($CP) {
        $this->CP = $CP;
    }

    function setVille($ville) {
        $this->ville = $ville;
    }

    function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setSitFam($sitFam) {
        $this->sitFam = $sitFam;
    }

    function setProfession($profession) {
        $this->profession = $profession;
    }

    function setNbEnfant($nbEnfant) {
        $this->nbEnfant = $nbEnfant;
    }

    function setAccouchement($accouchement) {
        $this->accouchement = $accouchement;
    }

    function setTypeHabitat($typeHabitat) {
        $this->typeHabitat = $typeHabitat;
    }

    function setAllergies($allergies) {
        $this->allergies = $allergies;
    }

    function setTraitementEnCours( $traitementEnCours) {
        $this->traitementEnCours = $traitementEnCours;
    }

    function setAtcdChirurgical($atcdChirurgical) {
        $this->atcdChirurgical = $atcdChirurgical;
    }

    function setAtcdFamiliaux($atcdFamiliaux) {
        $this->atcdFamiliaux = $atcdFamiliaux;
    }

    function setAtcdMedical($atcdMedical) {
        $this->atcdMedical = $atcdMedical;
    }

    function setContraception($contraception) {
        $this->contraception = $contraception;
    }

    function setObservations( $observations) {
        $this->observations = $observations;
    }

    function setAccepteMedNonTradi($accepteMedNonTradi) {
        $this->accepteMedNonTradi = $accepteMedNonTradi;
    }

    function setAccepteAcup($accepteAcup) {
        $this->accepteAcup = $accepteAcup;
    }
    function getNomsAffichage() {
        return $this->nomsAffichage;
    }

    function setNomsAffichage($nomsAffichage) {
        $this->nomsAffichage = $nomsAffichage;
    }

    function getActif() {
        return $this->actif;
    }

    function setActif($actif) {
        $this->actif = $actif;
    }



}
