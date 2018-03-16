<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Date;
/**
 * @ORM\Entity
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
     *
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
    * @ORM\Column(name="dateNaiss",type="date", nullable=true)
    * @Assert\Date()
    */
    private $dateNaiss ;
    
    public function __construct()
    {
      $this->date = '01/01/1900';
    }
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
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
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
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $dermatologue;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresseDermatologue;
    
 
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $osteopathe;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresseOsteopathe;
    
   
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $kine;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $adresseKine;
    
    
    
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $atcdChirurgical;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $atcdFamiliaux;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $atcdMedical;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $contraception;
    
    /**
     * @Assert\Date()
     * @ORM\Column(type="date", nullable=true)
     */
    private $derniereVisite;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $motifDerniereVisite;
    
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

    function getMedecinTraitant() {
        return $this->medecinTraitant;
    }

    function getAdresseMedTraitant() {
        return $this->adresseMedTraitant;
    }

    

    function getGynecologue() {
        return $this->gynecologue;
    }

    function getAdresseGynecologue() {
        return $this->adresseGynecologue;
    }

    function getDateDerVisiteGyneco(): date {
        return $this->dateDerVisiteGyneco;
    }

    function getCardiologue() {
        return $this->cardiologue;
    }

    function getAdresseCardiologue() {
        return $this->adresseCardiologue;
    }

    function getDateDerVisiteCardiologue(): date {
        return $this->dateDerVisiteCardiologue;
    }

    function getPhlebologue() {
        return $this->phlebologue;
    }

    function getAdressePhlebologue() {
        return $this->adressePhlebologue;
    }

    function getDateDerVisitePhlebologue(): date {
        return $this->dateDerVisitePhlebologue;
    }

    function getPediatre() {
        return $this->pediatre;
    }

    function getAdressePediatre() {
        return $this->adressePediatre;
    }

    function getDateDerVisitePediatre(): date {
        return $this->dateDerVisitePediatre;
    }

    function getDermatologue() {
        return $this->dermatologue;
    }

    function getAdresseDermatologue() {
        return $this->adresseDermatologue;
    }

    function getDateDerVisiteDermatologue(): date {
        return $this->dateDerVisiteDermatologue;
    }

    function getOsteopathe() {
        return $this->osteopathe;
    }

    function getAdresseOsteopathe() {
        return $this->adresseOsteopathe;
    }

    function getDateDerVisiteOsteopathe(): date {
        return $this->dateDerVisiteOsteopathe;
    }

    function getKine() {
        return $this->kine;
    }

    function getAdresseKine() {
        return $this->adresseKine;
    }

    function getDateDerVisiteKine(): date {
        return $this->dateDerVisiteKine;
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

    function getDerniereVisite(): date {
        return $this->derniereVisite;
    }

    function getMotifDerniereVisite(): text {
        return $this->motifDerniereVisite;
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

    function setMedecinTraitant($medecinTraitant) {
        $this->medecinTraitant = $medecinTraitant;
    }

    function setAdresseMedTraitant($adresseMedTraitant) {
        $this->adresseMedTraitant = $adresseMedTraitant;
    }

    function setDateDerVisiteMedTrait(date $dateDerVisiteMedTrait) {
        $this->dateDerVisiteMedTrait = $dateDerVisiteMedTrait;
    }

    function setGynecologue($gynecologue) {
        $this->gynecologue = $gynecologue;
    }

    function setAdresseGynecologue($adresseGynecologue) {
        $this->adresseGynecologue = $adresseGynecologue;
    }

    function setDateDerVisiteGyneco(date $dateDerVisiteGyneco) {
        $this->dateDerVisiteGyneco = $dateDerVisiteGyneco;
    }

    function setCardiologue($cardiologue) {
        $this->cardiologue = $cardiologue;
    }

    function setAdresseCardiologue($adresseCardiologue) {
        $this->adresseCardiologue = $adresseCardiologue;
    }

    function setDateDerVisiteCardiologue(date $dateDerVisiteCardiologue) {
        $this->dateDerVisiteCardiologue = $dateDerVisiteCardiologue;
    }

    function setPhlebologue($phlebologue) {
        $this->phlebologue = $phlebologue;
    }

    function setAdressePhlebologue($adressePhlebologue) {
        $this->adressePhlebologue = $adressePhlebologue;
    }

    function setDateDerVisitePhlebologue(date $dateDerVisitePhlebologue) {
        $this->dateDerVisitePhlebologue = $dateDerVisitePhlebologue;
    }

    function setPediatre($pediatre) {
        $this->pediatre = $pediatre;
    }

    function setAdressePediatre($adressePediatre) {
        $this->adressePediatre = $adressePediatre;
    }

    function setDateDerVisitePediatre(date $dateDerVisitePediatre) {
        $this->dateDerVisitePediatre = $dateDerVisitePediatre;
    }

    function setDermatologue($dermatologue) {
        $this->dermatologue = $dermatologue;
    }

    function setAdresseDermatologue($adresseDermatologue) {
        $this->adresseDermatologue = $adresseDermatologue;
    }

    function setDateDerVisiteDermatologue(date $dateDerVisiteDermatologue) {
        $this->dateDerVisiteDermatologue = $dateDerVisiteDermatologue;
    }

    function setOsteopathe($osteopathe) {
        $this->osteopathe = $osteopathe;
    }

    function setAdresseOsteopathe($adresseOsteopathe) {
        $this->adresseOsteopathe = $adresseOsteopathe;
    }

    function setDateDerVisiteOsteopathe(date $dateDerVisiteOsteopathe) {
        $this->dateDerVisiteOsteopathe = $dateDerVisiteOsteopathe;
    }

    function setKine($kine) {
        $this->kine = $kine;
    }

    function setAdresseKine($adresseKine) {
        $this->adresseKine = $adresseKine;
    }

    function setDateDerVisiteKine(date $dateDerVisiteKine) {
        $this->dateDerVisiteKine = $dateDerVisiteKine;
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

    function setDerniereVisite(date $derniereVisite) {
        $this->derniereVisite = $derniereVisite;
    }

    function setMotifDerniereVisite( $motifDerniereVisite) {
        $this->motifDerniereVisite = $motifDerniereVisite;
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


}
