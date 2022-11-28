<?
    include($_SERVER['DOCUMENT_ROOT'] . "/engine/header.php");

    Core::SetPageTitle('Тестовое задание');
    Core::GetComponent("companies", "main", []);

    include($_SERVER['DOCUMENT_ROOT'] . "/engine/footer.php");
?>