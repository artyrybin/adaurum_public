let socket = io("5.187.0.253:3030", {origins: "http://ad.yamete-kuda.site:3030"});

let notifications = {};

notifications.init = (currentUser) => {
    socket.on('sendNotifications', function(data) {
        if(currentUser !== data.userId && currentUser !== 0) {
            let notificationBox   = $('<div>');
            let notificationTitle = $('<div>');
            let notificationBody  = $('<div>');

            notificationBox.addClass('notificationBox');

            notificationTitle.addClass('notificationTitle').html(data.title);
            notificationBody.addClass('notificationBody').html(data.message);

            notificationBox.append(notificationTitle);
            notificationBox.append(notificationBody);

            $('.notificationsWrapper').append(notificationBox);
            notificationBox.slideDown();

            let thisTimeout = setTimeout(function() {
                notificationBox.slideUp();
            }, 6000);

            notificationBox.on('click', function() {
                clearTimeout(thisTimeout);
                notificationBox.slideUp();
            })
        }

    })
}

notifications.createNotifications = (userId, title, message) => {
    let sendData = {
        userId: userId,
        title: title,
        message: message
    };

    socket.emit('createNotifications', sendData);
}