createCompany = {};

createCompany.init = (data = []) => {

    let ajaxTarget = `#${data.COMPONENT_ID}[data-ajax="true"]`;

    $('body').on('submit', `${ajaxTarget} form`, function (event) {
        event.preventDefault(false);

        let formData = new FormData();
        let fileInput  = $(this).find('input[name="picture"]')[0];
        let formFields = $(this).serializeArray();

        formFields.forEach(function(e, i) {
            formData.append(e.name, e.value);
        })

        formData.append('picture', fileInput.files[0]);

        console.log(`${data.COMPONENT_PATH}ajax.php`);

        $.ajax({
            url: `${data.COMPONENT_PATH}ajax.php`,
            data: formData,
            method: 'POST',
            async: false,
            cache: false,
            processData: false,
            contentType: false,
            enctype: 'multipart/form-data',
            success: function(data) {
                $(ajaxTarget).html(data);
            }
        })

        return false;
    });

    // Translit

    $(document.body).on('keyup', `${ajaxTarget} input[name="name"]`, function() {
        let val = $(this).val();
        $(`${ajaxTarget} input[name="xml_id"]`).val(createCompany.translit(val));
    })

    $(document.body).on('keyup', `${ajaxTarget} input[name="xml_id"]`, function() {
        let val = $(this).val();
        $(this).val(createCompany.translit(val));
    })

    return true;
}

createCompany.translit = (word) => {
    let converter = {
        'а': 'a',    'б': 'b',    'в': 'v',    'г': 'g',    'д': 'd',
        'е': 'e',    'ё': 'e',    'ж': 'zh',   'з': 'z',    'и': 'i',
        'й': 'y',    'к': 'k',    'л': 'l',    'м': 'm',    'н': 'n',
        'о': 'o',    'п': 'p',    'р': 'r',    'с': 's',    'т': 't',
        'у': 'u',    'ф': 'f',    'х': 'h',    'ц': 'c',    'ч': 'ch',
        'ш': 'sh',   'щ': 'sch',  'ь': '',     'ы': 'y',    'ъ': '',
        'э': 'e',    'ю': 'yu',   'я': 'ya'
    };

    word = word.toLowerCase();

    let answer = '';
    for (var i = 0; i < word.length; ++i ) {
        if (converter[word[i]] == undefined){
            answer += word[i];
        } else {
            answer += converter[word[i]];
        }
    }

    answer = answer.replace(/[^-0-9a-z]/g, '_');
    answer = answer.replace(/[-]+/g, '_');
    answer = answer.replace(/^\-|-$/g, '');
    return answer;
}