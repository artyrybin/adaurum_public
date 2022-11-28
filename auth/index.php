<?php
include($_SERVER['DOCUMENT_ROOT'] . "/engine/header.php");
Core::SetPageTitle('Авторизация');
?>

    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="signForm">
                <div class="formTitle">
                    Sign In
                </div>
                <?
                Core::GetComponent(
                    'user.auth',
                    'main',
                    [],
                    []
                )
                ?>
            </div>
        </div>
    </div>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/engine/footer.php");
?>