<?php
    if(INDEX !== true) die();
    global $USER;
?>

<div id="<?=$data['UNIQUE_COMPONENT_ID']?>" data-ajax="true">
    <div class="cmlComments">
        <? if(count($data['COMMENTS'])): ?>
            <? foreach($data['COMMENTS'] as $comment): ?>
                <div class="cmlComment">
                    <div class="cmlUserInfo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <?=$data['USERS'][$comment['user_id']]['username']?>
                        <span>
                            <?=date("d.m.Y \\a\\t H:i", $comment['publish_date']);?>
                        </span>
                    </div>
                    <div class="cmlUserComment">
                        <?=nl2br($comment['text'])?>
                    </div>
                </div>
            <? endforeach; ?>
        <? else: ?>
            <div class="noComments">
                No comments yet
            </div>
        <? endif; ?>
    </div>

    <? if($USER->isAuthorized()):?>
        <? if($data['AJAX_RESULT']['RESULT'] == 'ERROR'): ?>
            <div class="alert alert-danger mt-4 alert-sm">
                <?=$data['AJAX_RESULT']['MESSAGE']?>
            </div>
        <? elseif($data['AJAX_RESULT']['RESULT'] == 'SUCCESS'): ?>
            <script>
                $(function() {
                    // Create notifications
                    let userId  = <?=$USER->getId();?>;
                    let title   = `New comment`;
                    let message = `
                                        User
                                        @<?=$USER->getInfo()['username']?>
                                        left a new comment
                                        «<?=$data['AJAX_RESULT']['DATA']['text']?>»
                                        on the
                                        <a href="/company/<?=$data['COMPANY']['xml_id']?>">«<?=$data['COMPANY']['name']?>»</a>
                                        page
                                    `;
                    console.log(userId);
                    notifications.createNotifications(userId, title, message);
                })
            </script>
        <? endif; ?>
        <form action="#" class="cmlMainForm cmlVisible">
            <input type="hidden" name="company_id" value="<?=$data['COMPANY_ID']?>">
            <textarea type="text" class="cmlInput" placeholder="Your message..." rows="1" name="message"></textarea>
            <button class="cmlSubmit">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" />
                </svg>
            </button>
        </form>
    <? endif; ?>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        comComments.init({
            COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
            COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
        });
    })
</script>