<?php
    if(INDEX !== true) die();
    global $USER;

    $comContainerUID = uniqid() . "_COM_CONTAINER_" . $data['FIELD_TYPE'];
    $comFormUID = uniqid() . "_COM_FORM_" . $data['FIELD_TYPE'];
?>

<div id="<?=$data['UNIQUE_COMPONENT_ID']?>" data-ajax="true">
    <div class="cmlActions">
        <button class="mainButton" data-action="note" data-form-id="<?=$comFormUID?>">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>

            Note
        </button>
    </div>

    <? if(count($data['NOTES'][$data['FIELD_TYPE']])): ?>
        <div class="cmlCommentsTitle">
            Notes
        </div>
        <div class="cmlComments" id="<?=$comContainerUID?>">
            <? foreach($data['NOTES'][$data['FIELD_TYPE']] as $note): ?>
                <div class="cmlComment">
                    <div class="cmlUserInfo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <?=$data['USERS'][$note['user_id']]['username'];?>

                        <span>
                            <?=date("d.m.Y \\a\\t H:i", $note['publish_date']);?>
                        </span>
                    </div>
                    <div class="cmlUserComment">
                        <?=nl2br($note['text'])?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>

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
                    let title   = `New note`;
                    let message = `
                                            User
                                            @<?=$USER->getInfo()['username']?>
                                            left a new note
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
        <form action="#" class="cmlMainForm noteForm <?=($data['AJAX_RESULT']['RESULT'] == 'ERROR' ? 'cmlVisible' : '')?>" id="<?=$comFormUID?>">
            <input type="hidden" name="company_id" value="<?=$data['COMPANY_ID']?>">
            <input type="hidden" name="field_type" value="<?=$data['FIELD_TYPE']?>">
            <textarea type="text" class="cmlInput" name="message" placeholder="Your message..." rows="1"></textarea>
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
        comNotes.init({
            COMPONENT_PATH: "<?=$data['COMPONENT_SITE_PATH']?>",
            COMPONENT_ID: "<?=$data['UNIQUE_COMPONENT_ID']?>",
        });
    })
</script>