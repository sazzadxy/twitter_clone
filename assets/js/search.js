$(function(){
    $('.search').keyup(function(){
        var search = $(this).val();
        $.post('http://localhost/twitter_clone/includes/ajax/search.php', {search:search}, function(data){
            $('.search-result').html(data);
        });
    });

    $(document).on('keyup', '.search-user', function () {
        $('.message-recent').hide();
        var search = $(this).val();
        $.post('http://localhost/twitter_clone/includes/ajax/searchUserInMsg.php', {search:search}, function (data) {
            $('.message-body').html(data);
        });
    });
});