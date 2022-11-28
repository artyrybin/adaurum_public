<? if(INDEX !== true) die(); ?>
        </div><!--/.container-->
    </div><!--/.mainWrapper-->

    <div class="notificationsWrapper"></div>

    <?php
        Core::AddJS($_SERVER['DOCUMENT_ROOT'] . '/template/js/jquery.min.js');
        Core::AddJS($_SERVER['DOCUMENT_ROOT'] . '/template/js/bootstrap.bundle.min.js');
        Core::AddJS($_SERVER['DOCUMENT_ROOT'] . '/template/js/ui.js');
        Core::AddJS($_SERVER['DOCUMENT_ROOT'] . '/template/js/main.js');
    ?>

    <script src="http://5.187.0.253:3030/socket.io/socket.io.js"></script>
    <script src="/template/js/socket.js"></script>

    <script>
        notifications.init(<?=$USER->getId();?>);
    </script>
</body>
</html>