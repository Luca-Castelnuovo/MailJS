// Load JS for MaterializeCSS
M.Modal.init(document.querySelectorAll('.modal'), {dismissible: false});
M.CharacterCounter.init(document.querySelectorAll('input.character-counter'));

/**
 * Create
 */
const createForm = document.querySelector('form.template-create');
createForm.addEventListener('submit', e => {
    e.preventDefault();
    const data = formDataToJSON(new FormData(createForm));

    disableAll(true);
    axios.post('/template', data).then(async () => {
        M.toast({html: 'Template Created'});
        
        M.Modal.getInstance(document.querySelector('.modal#template-create')).close();
        await delay(750);
        location.reload();
    }, error => {
        M.toast({html: 'Error'});
        
        disableAll(false);
        console.error(error);
    });
});

/**
 * Update
 */
const editForms = document.querySelectorAll('form.template-edit');
editForms.forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();
        const id = form.getAttribute('data-id');
        const data = formDataToJSON(new FormData(form));

        disableAll(true);
        axios.put(`/template/${id}`, data).then(async () => {
            M.toast({html: 'Template Updated'});
            
            M.Modal.getInstance(document.querySelector(`.modal#template-${id}-edit`)).close();
            await delay(750);
            location.reload();
        }, error => {
            M.toast({html: 'Error'});
            
            disableAll(false);
            console.error(error);
        });
    });
});

/**
 * Delete
 */
const deleteForms = document.querySelectorAll('form.template-delete');
deleteForms.forEach(form => 
    form.addEventListener('submit', e => {
        e.preventDefault();
        const id = form.getAttribute('data-id');
        const data = formDataToJSON(new FormData(form));

        disableAll(true);
        axios.delete(`/template/${id}`, data).then(async () => {
            M.toast({html: 'Template Deleted'});
            
            M.Modal.getInstance(document.querySelector(`.modal#template-${id}-delete`)).close();
            await delay(750);
            location.reload();
        }, error => {
            M.toast({html: 'Error'});
            
            disableAll(false);
            console.error(error);
        });
    })
);
