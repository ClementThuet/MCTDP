
    // Get the modal
    var modal = document.getElementById('myModal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById('imgStats');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.onclick = function()
    {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    };

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() { 
      modal.style.display = "none";
    };
    
    function genererLienAnnee()
    {
        var valInput=document.getElementById('inputAnnee').value;
        var pathToRedirect ="../statistiques-CA/annee-"+valInput;
        document.getElementById("lienGenerationAnnee").href=pathToRedirect; 
    }
    
    function genererLienMois()
    {
        var valInputMois=document.getElementById('inputMois').value;
        var valInputAnneeDuMois=document.getElementById('inputAnneeDuMois').value;
        var pathToRedirect ="../statistiques-CA/mois-"+valInputMois+'-'+valInputAnneeDuMois;
        document.getElementById("lienGenerationMois").href=pathToRedirect; 
    }
    function genererLienDates()
    {
        var valInputDate1=document.getElementById('inputDate1').value;
        var valInputDate2=document.getElementById('inputDate2').value;
        console.log(valInputDate1);
        console.log(valInputDate2);
        var pathToRedirect ='../statistiques-CA-dates/'+valInputDate1+'+'+valInputDate2;
        document.getElementById("lienGenerationDates").href=pathToRedirect; 
    }