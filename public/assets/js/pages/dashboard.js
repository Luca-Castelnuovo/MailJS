document.addEventListener('DOMContentLoaded', function(){
    M.Modal.init(document.querySelectorAll('.modal'), {dismissible: false});
    M.Sidenav.init(document.querySelectorAll('.sidenav'), {edge:"right"});
    M.CharacterCounter.init(document.querySelectorAll('.character-counter'));
    M.Collapsible.init(document.querySelectorAll('.collapsible'), {accordion: true});
});

document.addEventListener('DOMContentLoaded', function() {
    /**
     * Create
     */
    const createForm = document.querySelector('form.template-create');
    createForm.addEventListener('submit', e => {
        e.preventDefault();
        const data = formDataToJSON(new FormData(createForm));

        apiUse('POST', '/template', data);
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

            apiUse('POST', `/template/${id}`, data);
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

            apiUse('DELETE', `/template/${id}`, {});
        })
    );

    /**
     * Key Create
     */
    const keyCreateForms = document.querySelectorAll('form.template-key-create');
    keyCreateForms.forEach(form => 
        form.addEventListener('submit', e => {
            e.preventDefault();
            const id = form.getAttribute('data-id');
            const data = formDataToJSON(new FormData(form));

            apiUse('POST', `/template/${id}/key`, data);
        })
    );
    
    /**
     * Keys Revoke
     */
    const keyDeleteForms = document.querySelectorAll('form.template-key-delete');
    keyDeleteForms.forEach(form => 
        form.addEventListener('submit', e => {
            e.preventDefault();
            const id = form.getAttribute('data-id');

            apiUse('DELETE', `/template/${id}`, {});
        })
    );
});
