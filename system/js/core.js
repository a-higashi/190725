$("input[type=submit]#ok").click(function(e) {
    e.preventDefault();
    $(this).attr("disabled", "disabled");
    $('form').submit();
});
$(document).ready(function() {
    $('#datetime').toDate({
        format : 'Y/m/d （W） H:i:s'
    });
});
$(document).ready(function() {
  $(".check_flag").bootstrapSwitch();
});
function check_image(value)
{
    var file = $(value).prop('files')[0];
    console.log(file.type);
    if ( file.type == 'image/png' || file.type == 'image/jpg'|| file.type == 'image/jpeg' || file.type == 'image/gif' ) {
      return;
    } else {
      alert('画像以外のファイルをアップロード出来ません');
      $(value).val('');
      return;
    }
}
function check_pdf(value)
{
    var file = $(value).prop('files')[0];
    if ( file.type == 'application/pdf') {
      return;
    } else {
      alert('PDFファイル以外のファイルをアップロード出来ません');
      $(value).val('');
      return;
    }
}
/*
function check_image(value)
{
  var ext = $(value).val().split(".").pop();
  switch(ext.toLowerCase()){
    case "jpg":
    case "jpeg":
    case "gif":
    case "png":
    return true;
    break; //未到達コード
        default:
          alert('画像以外のファイルをアップロード出来ません');
          $(value).val('');
          return false;
          break; //未到達コード
  }
}
function check_pdf(value)
{
  var ext = $(value).val().split(".").pop();
  switch(ext.toLowerCase()){
    case "pdf":
    return true;
    break; //未到達コード
        default:
          alert('PDFファイル以外のファイルをアップロード出来ません');
          $(value).val('');
          return false;
          break; //未到達コード
  }
}
*/

function us_agent() {

  // ブラウザのUAを小文字で取得
  var userAgent = window.navigator.userAgent.toLowerCase();
   
  // 一般的なブラウザ判定
  if (userAgent.indexOf('msie') != -1) {
    /* IE. */
    return 'ie';
  } else if (userAgent.indexOf('chrome') != -1) {
    /* Google Chrome. */
    return 'chrome';
  } else if (userAgent.indexOf('firefox') != -1) {
    /* FireFox. */
    return 'firefox';
  } else if (userAgent.indexOf('safari') != -1) {
    /* Safari. */
    return 'safari';
  } else if (userAgent.indexOf('opera') != -1) {
    /* Opera. */
    return 'opera';
  } else if (userAgent.indexOf('gecko') != -1) {
    /* Gecko. */
    return 'gecko';
  } else {
    return false;
  }
}