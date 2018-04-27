<?php

namespace App\Service;

class CryptagePatient
{
    public function getCryptPatient($patient)
    {
        $key="abcdefghijklmnop";
        
        $encryptedNom = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getNom(), MCRYPT_MODE_ECB);
        $encryptedPrenom = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getPrenom(), MCRYPT_MODE_ECB);
        $encryptedNomsAffichage = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getNomsAffichage(), MCRYPT_MODE_ECB);
       /* $dateNaiss=$patient->getDateNaiss();
        $o = new ReflectionObject($dateNaiss);
        $p = $o->getProperty('date');
        $date = $p->getValue($dateNaiss);
        $encryptedDateNaiss = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$date, MCRYPT_MODE_ECB);*/
        $encryptedAdresse= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAdresse(), MCRYPT_MODE_ECB);
        $encryptedCP= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getCP(), MCRYPT_MODE_ECB);
        $encryptedVille= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getVille(), MCRYPT_MODE_ECB);
        $encryptedTelephone= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTelephone(), MCRYPT_MODE_ECB);
        $encryptedMail= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getMail(), MCRYPT_MODE_ECB);
        $encryptedsitFam= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getSitFam(), MCRYPT_MODE_ECB);
        $encryptedProfession= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getProfession(), MCRYPT_MODE_ECB);
        
        $encryptedNbEnfant= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getNbEnfant(), MCRYPT_MODE_ECB);
        $encryptedAccouchement= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAccouchement(), MCRYPT_MODE_ECB);
        $encryptedTypeHabitat= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTypeHabitat(), MCRYPT_MODE_ECB);
        $encryptedAllergies= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAllergies(), MCRYPT_MODE_ECB);
        $encryptedTraitementEnCours= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTraitementEnCours(), MCRYPT_MODE_ECB);
        $encryptedAtcdChirurgical= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdChirurgical(), MCRYPT_MODE_ECB);
        $encryptedAtcdFamiliaux= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdFamiliaux(), MCRYPT_MODE_ECB);
        $encryptedAtcdMedical= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdMedical(), MCRYPT_MODE_ECB);
        $encryptedContraception= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getContraception(), MCRYPT_MODE_ECB);
        $encryptedObservations= mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getObservations(), MCRYPT_MODE_ECB);
        
        
        $patient->setNom($encryptedNom);
        $patient->setPrenom($encryptedPrenom);
        $patient->setNomsAffichage($encryptedNomsAffichage);
        //$patient->setDateNaiss($encryptedDateNaiss);
        $patient->setAdresse($encryptedAdresse);
        $patient->setCP($encryptedCP);
        $patient->setVille($encryptedVille);
        $patient->setTelephone($encryptedTelephone);
        $patient->setMail($encryptedMail);
        $patient->setsitFam($encryptedsitFam);
        $patient->setProfession($encryptedProfession);
        $patient->setNbEnfant($encryptedNbEnfant);
        $patient->setAccouchement($encryptedAccouchement);
        $patient->setTypeHabitat($encryptedTypeHabitat);
        $patient->setAllergies($encryptedAllergies);
        $patient->setTraitementEnCours($encryptedTraitementEnCours);
        $patient->setAtcdChirurgical($encryptedAtcdChirurgical);
        $patient->setAtcdFamiliaux($encryptedAtcdFamiliaux);
        $patient->setAtcdMedical($encryptedAtcdMedical);
        $patient->setContraception($encryptedContraception);
        $patient->setObservations($encryptedObservations);
        
        return $patient;
    }
    
    public function getDecryptPatient($patient)
    {
        $key="abcdefghijklmnop";
       
        $decryptedNom = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $patient->getNom(), MCRYPT_MODE_ECB);
        $decryptedPrenom = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $patient->getPrenom(), MCRYPT_MODE_ECB);
        $decryptedNomsAffichage = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $patient->getNomsAffichage(), MCRYPT_MODE_ECB);
        $decryptedAdresse= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAdresse(), MCRYPT_MODE_ECB);
        $decryptedCP= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getCP(), MCRYPT_MODE_ECB);
        $decryptedVille= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getVille(), MCRYPT_MODE_ECB);
        $decryptedTelephone= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTelephone(), MCRYPT_MODE_ECB);
        $decryptedMail= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getMail(), MCRYPT_MODE_ECB);
        $decryptedsitFam= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getSitFam(), MCRYPT_MODE_ECB);
        $decryptedProfession= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getProfession(), MCRYPT_MODE_ECB);
        $decryptedNbEnfant= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getNbEnfant(), MCRYPT_MODE_ECB);
        $decryptedAccouchement= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAccouchement(), MCRYPT_MODE_ECB);
        $decryptedTypeHabitat= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTypeHabitat(), MCRYPT_MODE_ECB);
        $decryptedAllergies= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAllergies(), MCRYPT_MODE_ECB);
        $decryptedTraitementEnCours= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getTraitementEnCours(), MCRYPT_MODE_ECB);
        $decryptedAtcdChirurgical= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdChirurgical(), MCRYPT_MODE_ECB);
        $decryptedAtcdFamiliaux= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdFamiliaux(), MCRYPT_MODE_ECB);
        $decryptedAtcdMedical= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getAtcdMedical(), MCRYPT_MODE_ECB);
        $decryptedContraception= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getContraception(), MCRYPT_MODE_ECB);
        $decryptedObservations= mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key,$patient->getObservations(), MCRYPT_MODE_ECB);
        $patient->setNom($decryptedNom);
        $patient->setPrenom($decryptedPrenom);
        $patient->setNomsAffichage($decryptedNomsAffichage);
        $patient->setAdresse($decryptedAdresse);
        $patient->setCP($decryptedCP);
        $patient->setVille($decryptedVille);
        $patient->setTelephone($decryptedTelephone);
        $patient->setMail($decryptedMail);
        $patient->setsitFam($decryptedsitFam);
        $patient->setProfession($decryptedProfession);
        $patient->setNbEnfant($decryptedNbEnfant);
        $patient->setAccouchement($decryptedAccouchement);
        $patient->setTypeHabitat($decryptedTypeHabitat);
        $patient->setAllergies($decryptedAllergies);
        $patient->setTraitementEnCours($decryptedTraitementEnCours);
        $patient->setAtcdChirurgical($decryptedAtcdChirurgical);
        $patient->setAtcdFamiliaux($decryptedAtcdFamiliaux);
        $patient->setAtcdMedical($decryptedAtcdMedical);
        $patient->setContraception($decryptedContraception);
        $patient->setObservations($decryptedObservations);
        
        return $patient;
    }
    
    public function getDecryptListPatient($listPatientsCrypte,CryptagePatient $cryptagePatient)
    {
        $patient=null;
        foreach($listPatientsCrypte as $patient)
        {
            $listPatients[]=$cryptagePatient->getDecryptPatient($patient);
            
        }
        return $listPatients;
    }
}