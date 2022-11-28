let companyObject = {};

companyObject.init = (componentUrl) => {
    // Delete company
    $(document.body).on('submit', 'form.deleteCompany', function(event) {
        event.preventDefault(false);
        let data = {};
        let serialized = $(this).serializeArray();

        data.ACTION = 'DELETE_COMPANY';
        serialized.forEach(function(e) {
            data[e.name] = e.value;
        })

        companyObject.deleteCompany(data, componentUrl);

        return false;
    })
}

companyObject.deleteCompany = (data, componentUrl) => {
    console.log(data);
    $.ajax({
        url: `${componentUrl}ajax.php`,
        method: 'post',
        data: data,
        success: function(data) {
            window.location = '/';
        },
        error: function(data) {
            alert('Some error...');
        }
    })
}