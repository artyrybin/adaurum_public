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
            <input type="text" name="username" value="<?=$data['AJAX_RESULT']['USER_DATA']['username']?>" class="mainInput">
        </div>

        <div class="inputLabel">
            <label>Password:</label>
            <input type="password" name="password" class="mainInput">
        </div>

        <div class="inputLabel">
            <label>Repeat password:</label>
            <input type="password" name="password_repeat" class="mainInput">
        </div>

        <div class="text-center">
            <button class="mainButton mainButtonContrast">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z"></path>
                </svg>

                Create new account
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            userReg.init({
                COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
                COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
            });
        })
    </script>
</div>