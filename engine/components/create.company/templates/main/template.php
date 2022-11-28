<div id="<?=$data['UNIQUE_COMPONENT_ID']?>" data-ajax="true">
    <form enctype="multipart/form-data">
        <input type="hidden" name="FORM_ID" value="<?=$data['UNIQUE_COMPONENT_ID']?>">

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
            <? $data['AJAX_RESULT'] = []; ?>
        <? endif; ?>

        <div class="inputLabel">
            <label>Company name:</label>
            <input type="text" value="<?=$data['AJAX_RESULT']['DATA']['name']?>" name="name" class="mainInput">
        </div>

        <div class="inputLabel">
            <label>Company XML ID:</label>
            <input type="text" value="<?=$data['AJAX_RESULT']['DATA']['xml_id']?>" name="xml_id" class="mainInput">
        </div>

        <div class="inputLabel">
            <label>Company picture:</label>
            <input type="file" name="picture" class="mainInput">
        </div>

        <div class="innerModalFooter text-end">
            <button type="button" class="mainButton" onclick="mainUi.closeModal()">Cancel</button>
            <button type="submit" class="mainButton mainButtonContrast">Save</button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createCompany.init({
                COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
                COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
            });
        })
    </script>
</div>