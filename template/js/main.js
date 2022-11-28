$(function() {
    $(document.body).on('click', '[data-action]', function() {
        let thisAction = $(this).data('action');

        switch(thisAction) {
            case "note":
                let formId = $(this).data('form-id');
                $(`#${formId}`).toggleClass('cmlVisible');
                break;
            case "edit":
                let fieldId      = $(this).data('field-id'),
                    companyId    = $(this).data('company-id'),
                    currentValue = $(this).data('current-value');

                $('#fieldId').val(fieldId);
                $('#companyId').val(companyId);
                $('#newFieldValue').val(currentValue);

                mainUi.toggleModal('editModal');

                console.log(fieldId, companyId, currentValue);

                break;
        }
    })
})