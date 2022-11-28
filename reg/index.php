<?php
    include($_SERVER['DOCUMENT_ROOT'] . "/engine/header.php");
    Core::SetPageTitle('Регистрация нового пользователя');
?>

    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="signForm">
                <div class="formTitle">
                    Sign Up
                </div>
                <?
                    Core::GetComponent(
                        'user.reg',
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