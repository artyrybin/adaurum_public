comComments = {};

comComments.init = (data) => {
    let ajaxTarget = `#${data.COMPONENT_ID}[data-ajax="true"]`;

    $('body').on('submit', `${ajaxTarget} form`, function (event) {
        event.preventDefault(false);

        let sendData = $(this).serializeArray();

        console.log(`${data.COMPONENT_PATH}ajax.php`);

        $.ajax({
            url: `${data.COMPONENT_PATH}ajax.php`,
            data: sendData,
            method: 'post',
            success: function(data) {
                $(ajaxTarget).html(data);
            },
            error: function(data) {
                alert('Unknown error');
                console.log(data);
            }
        });

        return false;
    });
}