<?
if(INDEX !== true) die();

global $USER;

$item = $data['ITEM'];
?>

<? if(count($item)): ?>
<? $picture = ($item['picture'] ?: '/template/img/no_picture.png'); ?>

</div> <!-- .container -->

<div class="cmBanner">
    <img src="<?=$picture?>" alt="" class="bannerImage">
    <div class="cmBannerContent">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-6">
                    <h1 class="mainCompanyName">
                        <?=$item['name']?>
                    </h1>
                </div>
                <? if($USER->isAuthorized()):?>
                    <div class="col-6 text-end">
                        <button class="mainButton" data-action="edit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>

                            Edit
                        </button>
                        <button class="mainButton" onclick="mainUi.toggleModal('removeModal')">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>

                            Delete
                        </button>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="ccTitle">
                Information
            </div>
            <div class="companyInfoWrapper">
                <? foreach($data['FIELDS']['VALUES'] as $key => $field): ?>
                    <?
                        $fieldType = $data['FIELDS']['TYPES'][$field['type']];
                    ?>
                    <div class="companyMainLine<?=(empty($field['value']) ? ' lineHidden' : '')?>" data-line-id="<?=$fieldType['xml_id']?>">
                        <div class="infoLine">
                            <b><?=$fieldType['name']?>:</b>
                            <br>
                            <span data-input-name="<?=$fieldType['xml_id']?>"><?=$field['value']?></span>
                        </div>

                        <? if($USER->isAuthorized()):?>
                            <?
                                Core::GetComponent(
                                    'company.note',
                                    'main',
                                    [
                                        'COMPANY_ID' => $item['id'],
                                        'FIELD_TYPE' => $field['type'],
                                    ],
                                    []
                                );
                            ?>
                        <? endif; ?>
                    </div>
                <? endforeach; ?>
            </div>
        </div>

        <div class="col-md-4 mt-4 mt-sm-0">
            <div class="companyComments">
                <div class="ccTitle">
                    Comments
                </div>
                <?
                    Core::GetComponent(
                        'company.comments',
                        'main',
                        [
                            'COMPANY_ID' => $item['id']
                        ],
                        []
                    );
                ?>
            </div>
        </div>
    </div>

    <? if($USER->isAuthorized()): ?>
        <div class="mainModal" id="editModal">
            <div class="mainModalWindow">
                <div class="mainModalClose" onclick="mainUi.closeModal();"></div>
                <div class="mainModalTitle">
                    Edit field
                </div>
                <div class="mainModalContent">
                    <?
                        Core::GetComponent(
                            'edit.form',
                            'main',
                            [
                                'COMPANY_ID' => $item['id']
                            ],
                            []
                        );
                    ?>
                </div>
            </div>
        </div>

        <div class="mainModal" id="removeModal">
            <div class="mainModalWindow">
                <div class="mainModalClose" onclick="mainUi.closeModal();"></div>
                <div class="mainModalTitle">
                    Delete company
                </div>
                <div class="mainModalContent">
                    <form class="deleteCompany">
                        <input type="hidden" name="COMPANY_ID" value="<?=$item['id']?>">
                        <div class="alert alert-danger text-center">
                            Are you sure you want to delete this item? <br>The action cannot be undone.
                        </div>
                        <div class="innerModalFooter text-end">
                            <button type="button" class="mainButton" onclick="mainUi.closeModal()">Cancel</button>
                            <button type="submit" class="mainButton mainButtonContrast">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <? endif; ?>

    <script>
        companyObject.init('<?=$data['COMPONENT_SITE_PATH']?>');
    </script>

    <? else: ?>
        <div class="notFound">
            Nothing found :(
        </div>
    <? endif; ?>
