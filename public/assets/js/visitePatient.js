var divRegl = document.getElementById('divReglement');
var btRegl =document.getElementById('btReglement');
//Masquage de la div des moyens de réglement
divRegl.style.display = "none";

//Affichage des bt 'cheques' et 'especes" au click sur "reglement"
btRegl.onclick= function(){
    divRegl.style.display = "";
};
