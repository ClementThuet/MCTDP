var tr = document.getElementsByClassName('parent');
var saisieNom = document.getElementById('searchNom');
var saisiePrenom = document.getElementById('searchPrenom');

//recherche sur le nom
saisieNom.onkeyup = function() {
    tds= document.getElementsByClassName('searchNom');
    //Parcours de tous les tr avec class 'searchNom'
    for (var i=0;i<tds.length;i++){
        //Parcours du nom dans le TR
        for (var x=0;x<=saisieNom.value.length;x++)
        { 
            encours=tds[i].innerText.substring(0,x);
            if (saisieNom.value === encours)
            {
                tr[i].style.display='';
            }
            else
            {   
                tr[i].style.display='none';
            }
        }
    }
};
//recherche sur le prenom
saisiePrenom.onkeyup = function() {
    tds= document.getElementsByClassName('searchPrenom');
    
    //Parcours de tous les tr avec class 'searchPrenom'
    for (var i=0;i<tds.length;i++){
        //Parcours du nom dans le TR
        for (var x=0;x<=saisiePrenom.value.length;x++)
        { 
            encours=tds[i].innerText.substring(0,x);
            if (saisiePrenom.value === encours)
            {
                tr[i].style.display='';
            }
            else
            {   
                tr[i].style.display='none';
            }
        }
    }
};
    
