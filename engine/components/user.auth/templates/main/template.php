<div id="<?=$data['UNIQUE_COMPONENT_ID']?>" data-ajax="true">
    <form>
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
        <? endif; ?>

        <div class="inputLabel">
            <label>Username:</label>
            <input type="text" name="username" class="mainInput">
        </div>

        <div class="inputLabel">
            <label>Password:</label>
            <input type="password" name="password" class="mainInput">
        </div>

        <div class="text-center">
            <button class="mainButton mainButtonContrast">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M19 10a.75.75 0 00-.75-.75H8.704l1.048-.943a.75.75 0 10-1.004-1.114l-2.5 2.25a.75.75 0 000 1.114l2.5 2.25a.75.75 0 101.004-1.114l-1.048-.943h9.546A.75.75 0 0019 10z" clip-rule="evenodd" />
                </svg>

                Sign In
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            userAuth.init({
                COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
                COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
            });
        })
    </script>
</div>