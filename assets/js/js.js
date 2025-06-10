

const abrirLoja = document.getElementById('AbrirLoja')
const abrimodal1 = document.getElementById('loja')
const abrirLoja2 = document.getElementById('AbrirLoja2')
const abrimodal2 = document.getElementById('loja2')
const abrirLoja3 = document.getElementById('AbrirLoja3')
const abrimodal3 = document.getElementById('loja3')

let timeoutId = null; // Defina timeoutId como null inicialmente
let timeoutId2= null; // Defina timeoutId como null inicialmente
let timeoutId3 = null; // Defina timeoutId como null inicialmente

function abrirmodal() {
  

  if(timeoutId !== null) {
    clearTimeout(timeoutId)
  }
  
  abrimodal1.style.display = 'block'

}


function fecharmodal() {
  timeoutId = setTimeout(function() {
   abrimodal1.style.display = 'none'
   
  }, 200)
 

}

function abrirmodal2() {
 

  if(timeoutId2 !== null) {
     clearTimeout(timeoutId2)
  }
  
  abrimodal2.style.display = 'block'
  

}


function fecharmodal2() {
   timeoutId2 = setTimeout(function() {
    abrimodal2.style.display = 'none'
    
   }, 200)
  

}


function abrirmodal3() {
 
  if(timeoutId3 !== null){
     clearTimeout(timeoutId3)
  }
  abrimodal3.style.display = 'block'

}


function fecharmodal3() {
  timeoutId3 = setTimeout(function() {
   abrimodal3.style.display = 'none'
   
  }, 200)
 

}



abrirLoja.addEventListener('mouseover', abrirmodal)
abrirLoja.addEventListener('mouseout', fecharmodal)
abrirLoja2.addEventListener('mouseover', abrirmodal2)
abrirLoja2.addEventListener('mouseout', fecharmodal2)
abrirLoja3.addEventListener('mouseover', abrirmodal3)
abrirLoja3.addEventListener('mouseout', fecharmodal3)