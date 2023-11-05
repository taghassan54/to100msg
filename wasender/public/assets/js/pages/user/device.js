   "use strict";
   
   const base_url  = $('#base_url').val();
  
   $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
   });

   $.ajax({
    type: 'POST',
    url: base_url+'/user/device-statics',
    dataType: 'json',
    success: function(response) {
        $('#total-device').html(response.total);
        $('#total-inactive').html(response.inActive);
        $('#total-active').html(response.active);
    }
   });


   const copyButton = document.getElementById('copyButton');
   const textToCopy = document.getElementById('textToCopy');

   copyButton.addEventListener('click', function () {
       // Create a temporary textarea to hold the text
       const textarea = document.createElement('textarea');
       textarea.value = textToCopy.textContent;
       document.body.appendChild(textarea);

       // Select and copy the text
       textarea.select();
       document.execCommand('copy');

       // Clean up and provide feedback
       document.body.removeChild(textarea);
       alert('Text copied to clipboard');
   });