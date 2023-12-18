/* 
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

document.getElementById('show10').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'c_suivreFiches.php?a=getResumeFiche&b=10', true);
    xhr.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200){
            console.log(this.responseText);
        }
    };
    xhr.send();
});

document.getElementById('show20').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'c_suivreFiches.php?a=getResumeFiche&b=20', true);
    xhr.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200){
            console.log(this.responseText);
        }
    };
    xhr.send();
});

document.getElementById('show30').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'c_suivreFiches.php?a=getResumeFiche&b=30', true);
    xhr.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200){
            console.log(this.responseText);
        }
    };
    xhr.send();
});

document.getElementById('show50').addEventListener('click', function() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'c_suivreFiches.php?a=getResumeFiche&b=50', true);
    xhr.onreadystatechange = function() {
        if(this.readyState === 4 && this.status === 200){
            console.log(this.responseText);
        }
    };
    xhr.send();
});