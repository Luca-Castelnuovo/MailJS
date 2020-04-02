// Load JS for MaterializeCSS
M.Modal.init(document.querySelectorAll('.modal'), {dismissible: false});
M.CharacterCounter.init(document.querySelectorAll('.character-counter'));

// Bootstrap axios instance
const api = axios.create({
    baseURL: '/',
    headers: {
        'Content-Type': 'application/json'
    }
});

/**
 * Create
 */
const createForm = document.querySelector('form.template-create');
createForm.addEventListener('submit', e => {
    e.preventDefault();
    const data = formDataToJSON(new FormData(createForm));

    disableAll();
    api.post('/template', data).then(async () => {
        M.toast({html: 'Template Created'});
        
        M.Modal.getInstance(document.querySelector('.modal#template-create')).close();
        await delay(750);
        location.reload();
    }, async error => {
        M.toast({html: 'Error'});
        
        console.error(error);
        await delay(750);
        location.reload();
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

        disableAll();
        api.put(`/template/${id}`, data).then(async () => {
            M.toast({html: 'Template Updated'});
            
            M.Modal.getInstance(document.querySelector(`.modal#template-${id}-edit`)).close();
            await delay(750);
            location.reload();
        }, async error => {
            M.toast({html: 'Error'});
            
            console.error(error);
            await delay(750);
            location.reload();
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

        disableAll();
        api.delete(`/template/${id}`).then(async () => {
            M.toast({html: 'Template Deleted'});
            
            M.Modal.getInstance(document.querySelector(`.modal#template-${id}-delete`)).close();
            await delay(750);
            location.reload();
        }, async error => {
            M.toast({html: 'Error'});
            
            console.error(error);
            await delay(750);
            location.reload();
        });
    })
);
