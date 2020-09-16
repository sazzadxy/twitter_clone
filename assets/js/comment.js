$(function () {
    $(document).on('click', '#postComment', function () {
        var comment = $('#commentField').val();
        var tweet_id = $('#commentField').data('tweet');

        if (comment != "") {
            $.post('http://localhost/twitter_clone/includes/ajax/comment.php', { comment: comment, tweet_id: tweet_id }, function (data) {
                $('#comments').html(data);
                $('#commentField').val("");
            });
        }


        // function maxLength(el) {
        //     if (!('maxLength' in el)) {
        //         var max = el.attributes.maxLength.value;
        //         el.onkeypress = function () {
        //             if (this.value.length >= max) return false;
        //         };
        //     }
        // }
        // maxLength(document.getElementsByClassName("t-fo-right"));
    });
});