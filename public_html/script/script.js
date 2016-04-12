function aggiornaTipo(elem){
  if(elem.options[0].selected){
    document.getElementById("1_abb").style.display="none";
    document.getElementById("1_att").style.display="none";
    document.getElementById("1_prot").style.display="none";
  }  

  if(elem.options[1].selected){
    document.getElementById("1_abb").style.display="block";        
    document.getElementById("1_att").style.display="none";
    document.getElementById("1_prot").style.display="none";
  }
  
  if(elem.options[2].selected){
    document.getElementById("1_abb").style.display="none";
    document.getElementById("1_att").style.display="block";
    document.getElementById("1_prot").style.display="none";
  }

  if(elem.options[3].selected){
    document.getElementById("1_abb").style.display="none";
    document.getElementById("1_att").style.display="none";
    document.getElementById("1_prot").style.display="block";
  }
}

function upbutton(elem){
  if(elem.options[0].selected){
    document.add.button.disabled=true;
  }
  else{
    document.add.button.disabled=false;
  }
}