$(function(){
    $(document).on('click', '.addTweetBtn', function() {
        $('.status').removeClass().addClass('status-removed');
        $('.hash-box').removeClass().addClass('hash-removed');
        $('#count').attr('id','count-removed');
    
    
        $.post('http://localhost/twitter_clone/includes/ajax/tweetForm.php', function(data){
            $('.popupTweet').html(data);
            $('.closeTweetPopup').click(function(){
                $('.popup-tweet-wrap').hide();
                $('.status-removed').removeClass().addClass('status');
                $('.hash-removed').removeClass().addClass('hash-box');
                $('#count-removed').attr('id','count');
            });
        });
    });

    $(document).on('submit', '#popupForm', function (e) {
        e.preventDefault();
        var formData = new FormData($(this)[0]);
        formData.append('file', $('#file')[0].files[0]);
        $.ajax({
            url: "http://localhost/twitter_clone/includes/ajax/addTweet.php",
            type: "POST",
            data: formData,
            success: function (data) {
                result = JSON.parse(data);

                if (result['error']) {
                    $('<div class="error-banner"><div class="error-banner-inner"><div class="error-banner-content"><p id="errorMsg" style="margin-top:20px;">'+result.error+'</p></div></div></div>').insertAfter('.header-wrapper');
                    $('.error-banner').hide().slideDown(300).delay(5000).slideUp(300);
                    $('.popup-tweet-wrap').hide();
                } else if (result['success']) {
                    $('<div class="error-banner"><div class="error-banner-inner"><div class="error-banner-content"><p id="errorMsg" style="margin-top:20px;">'+result.success+'</p></div></div></div>').insertAfter('.header-wrap');
                    $('.error-banner').hide().slideDown(300).delay(5000).slideUp(300);
                    $('.popup-tweet-wrap').hide();
                }

            },
            cache: false,
            contentType: false,
            processData: false

        });
    });

});

