<?php
if(INDEX !== true) die();
global $USER;
?>

<? if($USER->isAuthorized()): ?>
    <div class="companiesLine mb-4">
        <div class="text-end">
            <button class="mainButton" onclick="mainUi.toggleModal('createCompany')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New company
            </button>
        </div>
    </div>
<? endif; ?>

<? if(count($data['ITEMS'])): ?>
    <div class="row">
        <? foreach($data['ITEMS'] as $i => $item): ?>
            <? $picture = ($item['picture'] ?: '/template/img/no_picture.png'); ?>
            <div class="col-md-4">
                <div class="companyItem">
                    <a href="/company/<?=$item['xml_id']?>" class="fullHeightLink"></a>
                    <div class="companyPicture">
                        <img src="<?=$picture?>" alt="">
                    </div>
                    <div class="companyName">
                        <?=$item['name']?>
                    </div>
                    <div class="companyInfo">

                        <? foreach($data['FIELDS']['VALUES'] as $key => $field): ?>
                            <? $fieldType = $data['FIELDS']['TYPES'][$field['type']]; ?>
                            <? if($field['company_id'] == $item['id'] && $fieldType['card_show'] && !empty($field['value'])): ?>
                                <div class="companyLine">
                                    <b><?=$fieldType['name']?>:</b>
                                    <?=$field['value']?>
                                </div>
                            <? endif; ?>
                        <? endforeach; ?>

                        <div class="viewMore">
                            View more

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
<? else: ?>
    <div class="notFound">
        Nothing found :(
    </div>
<? endif; ?>

<? if($USER->isAuthorized()): ?>
    <div class="companiesLine mt-4">
        <div class="text-end">
            <button class="mainButton" onclick="mainUi.toggleModal('createCompany')">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                New company
            </button>
        </div>
    </div>

    <div class="mainModal" id="createCompany">
        <div class="mainModalWindow">
            <div class="mainModalClose" onclick="mainUi.closeModal();"></div>
            <div class="mainModalTitle">
                Delete company
            </div>
            <div class="mainModalContent">
                <?
                    Core::GetComponent(
                        'create.company',
                        'main',
                        [],
                        []
                    )
                ?>
            </div>
        </div>
    </div>
<? endif; ?>