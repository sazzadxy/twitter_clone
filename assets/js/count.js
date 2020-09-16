// var maxLength = 140;
// $('textarea').keyup(function(){
//    var textlen = maxLength - $(this).val().length;
//    $('#count').text(textlen);
// });

function maxLength(el) {
   if (!('maxLength' in el)) {
      var max = el.attributes.maxLength.value;
      el.onkeypress = function(){
         if (this.value.length >= max) return false;
      };
   }
}

maxLength(document.getElementsByClassName("status"));
