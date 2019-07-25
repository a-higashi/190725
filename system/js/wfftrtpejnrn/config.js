/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here. For example:
  // config.language = 'fr';
  // config.uiColor = '#AADC6E';
  // Define changes to default configuration here. For example:
  config.language = 'ja';
  // config.uiColor = '#AADC6E';
  config.contentsCss = ['template/cmn/css/basic.css', 'template/cmn/css/style.css', 'body{width:555px;height:auto;margin:10px;}'];
  config.coreStyles_bold = { element: 'b', overrides: 'strong' };
/** ALL **/
  CKEDITOR.config.toolbar = [
      ['Source','-','NewPage','Preview']
      ,['Cut','Copy','Paste','-','Undo','Redo']
      ,['Bold','Underline','Strike']
      //,['NumberedList','BulletedList']
      ,['JustifyLeft','JustifyCenter','JustifyRight']
      ,['Link','Unlink','-','Image']
      ,['FontSize']
      ,['TextColor','BGColor']
      //,['Templates']
      ];
  config.bodyClass = '';
  config.width = '100%';
  config.height = '250px';
  config.format_tags = 'p;h2;h3;h4;h5;h6;pre;div';
  config.format_p = { element: 'p', attributes: { 'class': 'text_area' } };
  config.templates_files = ['/system/js/wfftrtpejnrn/plugins/templates/templates/default.js'];
  config.templates = 'default';
  config.templates_replaceContent = false;
  config.font_names='ＭＳ Ｐゴシック;ＭＳ Ｐ明朝;ＭＳ ゴシック;ＭＳ 明朝;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS, cursive;Courier New/Courier New, Courier, monospace;Georgia/Georgia, serif;Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;Verdana/Verdana, Geneva, sans-serif';

  // タグのフィルタリングを無効にして任意の記述を許す
  config.allowedContent = true;

  //ダイアログ：カスタマイズ
  CKEDITOR.on( 'dialogDefinition', function( ev ) {
    //定義中のダイアログ種類名を取得する
    var dialogName = ev.data.name;

    //ダイアログ定義を取得する
    var dialogDefinition = ev.data.definition;

    //全ダイアログ共通してリサイズを無効にする
    dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_NONE;
    //ダイアログ個別に指定する
    if ( dialogName == 'link' )
    {
      //縦横方向のリサイズを許可する
      dialogDefinition.resizable = CKEDITOR.DIALOG_RESIZE_BOTH;

      //ダイアログ初期表示サイズ
      dialogDefinition.width  = 380;
//      dialogDefinition.height = 480;
    }

    var stylesSet = [
      { name: 'Medium border', type: 'widget', widget: 'image', attributes: { 'class': 'mediumBorder' } },
      { name: 'Thick border', type: 'widget', widget: 'image', attributes: { 'class': 'thickBorder' } },
      { name: 'So important', type: 'widget', widget: 'image', attributes: { 'class': 'important soMuch' } },

      { name: 'Red marker', type: 'widget', widget: 'placeholder', attributes: { 'class': 'redMarker' } },
      { name: 'Invisible Placeholder', type: 'widget', widget: 'placeholder', attributes: { 'class': 'invisible' } },

      { name: 'Invisible Mathjax', type: 'widget', widget: 'mathjax', attributes: { 'class': 'invisible' } }
    ];

  });
  CKEDITOR.dtd.$removeEmpty.i = 0;

  CKEDITOR.on('instanceReady', function(ev) {
      ev.editor.dataProcessor.writer.indentationChars = '';
      // 処理対象タグ
      var tags = ['div',
                  'h1','h2','h3','h4','h5','h6',
                  'p',
                  'ul','ol','li','dl','dt','dd',
                  'table','thead','tbody','tfoot','tr','th','td',
                  'pre', 'address'];

      for (var key in tags) {
          ev.editor.dataProcessor.writer.setRules(tags[key], {
              breakAfterOpen : false
          });
      }
  });
  /*
  CKEDITOR.on( 'dialogDefinition', function( ev ) {
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        if ( dialogName == 'link' ){
          dialogDefinition.removeContents( 'advanced' );
        }
        if ( dialogName == 'image' ){
          dialogDefinition.removeContents( 'advanced' );
        }
      } )
  */
};
