var isAllSelected = false

window.addEventListener('load', () => {
    
    var btnAll = document.getElementById('selectAllBtn');
    
    btnAll.addEventListener('click', () => {
        var fiches = Array.from(document.getElementsByClassName('ficheCheckbox'));
        
        fiches.forEach((element) => {
            if (isAllSelected) {
                element.checked = true;
                btnAll.textContent = 'bob'
            }
            else {
                element.checked = false;
            }
        });
        
        btnAll.textContent = isAllSelected ? 'Désélectionner tous' : 'Sélectionner tous';
        
        isAllSelected = !isAllSelected;
    });
});