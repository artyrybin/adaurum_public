userReg = {};

userReg.init = (regData) => {
    let ajaxTarget = `#${regData.COMPONENT_ID}[data-ajax="true"]`;


    $('body').on('submit', `${ajaxTarget} form`, function (event) {
        let data = $(`${ajaxTarget} form`).serialize();

        $.ajax({
            url: `${regData.COMPONENT_PATH}ajax.php`,
            method: 'post',
            //dataType: 'json',
            data: data,
            success: function(data) {
                $(ajaxTarget).html(data);
            }
        });

        return false;
    });
}