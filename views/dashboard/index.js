document.addEventListener('DOMContentLoaded', function() {
    // Load JS for MaterializeCSS
    const modals = document.querySelectorAll('.modal');
    M.Modal.init(modals, {
        dismissible: false
    });

    const inputs = document.querySelectorAll('input.character-counter');
    M.CharacterCounter.init(inputs);

    // Create btn
    document.querySelector("button[data-action='create']").addEventListener("click", () => {
        // TODO: validate form
        // TODO: get data from form
    
        axios.post('/template').then(async () => {
            M.toast({html: 'Template Created'});
            
            await delay(750);
            location.reload();
        }, (error) => {
            M.toast({html: 'Error'});
            
            console.error(error);
        });
    });
    
    // Update btn
    document.querySelectorAll("button[data-action='edit']").forEach(editBtn => 
        editBtn.addEventListener("click", () => {
            const templateID = editBtn.getAttribute("data-id");
            const templateCardTitle = document.querySelector(`#template-${templateID} > .card > .card-content > .card-title`);
            
            // TODO: validate form
            // TODO: get data from form
    
            axios.put(`/template/${templateID}`).then(async () => {
                M.toast({html: 'Template updated'});
    
                // update title
                templateCardTitle.textContent = 'Updated Title';
            }, (error) => {
                M.toast({html: 'Error'});
                
                console.error(error);
            });
        })
    );
    
    // Delete btn
    document.querySelectorAll("button[data-action='delete']").forEach(deleteBtn => 
        deleteBtn.addEventListener("click", () => {
            const templateID = deleteBtn.getAttribute("data-id");
            const templateCard = document.querySelector(`#template-${templateID}`);
            
            axios.delete(`/template/${templateID}`).then(async () => {
                M.toast({html: 'Template deleted'});
                
                templateCard.classList.add("scale-out");
                await delay(250);
                templateCard.remove();
            }, (error) => {
                M.toast({html: 'Error'});
                
                console.error(error);
            });
        })
    );
});
