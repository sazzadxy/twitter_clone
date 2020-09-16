$(function () {
    $(document).on('click', '#messagePopup', function () {
        var getMessages = 1;
        $.post('http://localhost/twitter_clone/includes/ajax/messages.php', { showMessage: getMessages }, function (data) {
            $('.popupTweet').html(data);
            $('#messages').hide();
        });
    });
    

    $(document).on('click', '.people-message', function () {
        var get_id = $(this).data('user');
        $.post('http://localhost/twitter_clone/includes/ajax/messages.php', { showChatPopup: get_id }, function (data) {
            $('.popupTweet').html(data);

            if (autoscroll) {
                scrollDown();
            }

            $('#chat').on('scroll', function () {
                if ($(this).scrollTop() < this.scrollHeight - $(this).height()) {
                    autoscroll = false;
                } else {
                    autoscroll = true;
                }
            });

            $('.close-msgPopup').click(function () {
                clearInterval(timer);
            });
        });

        getMessages = function () {
            $.post('http://localhost/twitter_clone/includes/ajax/messages.php', { showChatMessage: get_id }, function (data) {
                $('.main-msg-inner').html(data);
                if (autoscroll) {
                    scrollDown();
                }

                $('#chat').on('scroll', function () {
                    if ($(this).scrollTop() < this.scrollHeight - $(this).height()) {
                        autoscroll = false;
                    } else {
                        autoscroll = true;
                    }
                });

                $('.close-msgPopup').click(function () {
                    clearInterval(timer);
                });
            });
        }

        var timer = setInterval(getMessages, 5000);
        getMessages();

        autoscroll = true;
        scrollDown = function () {
            $('#chat').scrollTop($('#chat')[0].scrollHeight);
        }

        $(document).on('click', '.back-messages', function () {
            var getMessages = 1;
            $.post('http://localhost/twitter_clone/includes/ajax/messages.php', { showMessage: getMessages }, function (data) {
                $('.popupTweet').html(data);
                clearInterval(timer);
            });
        });

        $(document).on('click', '.deleteMsg', function () {
            var messageID = $(this).data('message');
            $('.message-del-inner').hide('100px');
            
            $(document).on('click', '.cancel', function () {
                $('.message-del-inner').hide('0px');
            });

            $(document).on('click', '.delete', function () {
                $.post('http://localhost/twitter_clone/includes/ajax/messages.php', {deleteMsg:messageID}, function (data) {
                    $('.message-del-inner').hide('0px');
                    getMessages();
                });
                
            });

        });

    });
});