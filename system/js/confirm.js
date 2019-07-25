      $(function(){
          $('#preview').click(function() {
              $('form').attr('target', '_blank');
              $('#mode').val('detail');
              $('#form').submit();
          });
          $('#ok').click(function() {
              $('form').attr('target', '_self');
              $('#mode').val('commit');
              $('#form').submit();
          });
          $('#cancel').click(function() {
              $('form').attr('target', '_self');
              $('#mode').val('commit');
              $('#form').submit();
          });
          $('#lists').click(function() {
              $('form').attr('target', '_self');
              $('#mode').val('commit');
              $('#form').submit();
          });
      });
