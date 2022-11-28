<?
    @include($_SERVER['DOCUMENT_ROOT'] . '/engine/header.php');
    Buffer::getInstance()->setProperty("PAGE_TITLE", "404 - Страница не найдена");
?>

<div class="main">
    <div class="container" style="text-align: center;">
        <h1>Error 404</h1>
        <p>Nothing found :(</p>
        <a href="/" class="mainButton">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" />
            </svg>
            Go Home
        </a>
    </div>
</div>