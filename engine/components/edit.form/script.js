editCompany = {};

editCompany.init = (editData = []) => {
    let ajaxTarget = `#${editData.COMPONENT_ID}[data-ajax="true"]`;

    $('body').on('submit', `${ajaxTarget} form`, function (event) {
        let data = $(`${ajaxTarget} form`).serialize();

        $.ajax({
            url: `${editData.COMPONENT_PATH}ajax.php`,
            method: 'post',
            //dataType: 'json',
            data: data,
            success: function(data) {
                $(ajaxTarget).html(data);
                let receivedData = $(data).find('form').serializeArray();
                receivedData.forEach(function(e, i) {
                    let inputName  = e.name;
                    let inputValue = e.value;

                    // Исключения

                    if(inputName === 'company_name') {
                        $('.mainCompanyName').text(inputValue);
                    } else if(inputName === 'xml_id') {
                        // Подмена URL при смене XML ID
                        window.history.pushState("", "", `/company/${inputValue}`);
                    } else {

                        // Основные поля

                        $(`[data-input-name='${inputName}']`).text(inputValue);

                        if (inputValue.length !== 0) {
                            $(`[data-line-id='${inputName}']`).removeClass('lineHidden');
                        } else {
                            $(`[data-line-id='${inputName}']`).addClass('lineHidden');
                        }
                    }
                })
            }
        });

        return false;
    });
}