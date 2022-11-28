<div id="<?=$data['UNIQUE_COMPONENT_ID']?>" data-ajax="true">
    <form>
        <input type="hidden" name="FORM_ID" value="<?=$data['UNIQUE_COMPONENT_ID']?>">
        <input type="hidden" name="COMPANY_ID" value="<?=$data['COMPANY_ID']?>">

        <? if($data['AJAX_RESULT']['RESULT'] == 'ERROR'): ?>
            <div class="alert alert-danger">
                <?=$data['AJAX_RESULT']['MESSAGE']?>
            </div>
        <? elseif($data['AJAX_RESULT']['RESULT'] == 'SUCCESS'): ?>
            <? if($data['AJAX_RESULT']['ACTION'] == 'REDIRECT'): ?>
                <script>
                    window.location = '<?=$data['AJAX_RESULT']['URL'];?>'
                </script>
            <? else: ?>
                <div class="alert alert-success">
                    <?=$data['AJAX_RESULT']['MESSAGE']?>
                </div>
            <? endif; ?>
        <? endif; ?>

        <div class="inputLabel">
            <label>Company name:</label>
            <input type="text" name="company_name" value="<?=$data['COMPANY']['name']?>" class="mainInput">
        </div>

        <? if(false): ?>
            <div class="inputLabel">
                <label>Company XML_ID:</label>
                <input type="text" name="xml_id" value="<?=$data['COMPANY']['xml_id']?>" class="mainInput">
            </div>
        <? endif; ?>

        <? foreach($data['FIELDS'] as $field): ?>
            <div class="inputLabel">
                <label><?=$field['name']?>:</label>
                <input type="text" class="mainInput" name="<?=$field['xml_id']?>" value="<?=$field['value']?>">
            </div>
        <? endforeach; ?>

        <div class="innerModalFooter text-end">
            <button type="button" class="mainButton" onclick="mainUi.closeModal()">Cancel</button>
            <button type="submit" class="mainButton mainButtonContrast">Save</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        editCompany.init({
            COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
            COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
            COMPANY_ID: <?=$data['COMPANY_ID']?>,
        });
    })
</script>