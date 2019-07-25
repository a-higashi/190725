/*
 Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
/*
CKEDITOR.addTemplates("default",{imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"templates/images/"),
templates:[{title:"見出し2",image:"template_h2.gif",description:"見出し用テンプレート2です。",html:'<h2 class="in-title">村本建設はお客様保管の微量ＰＣＢ廃棄物処理の<br>トータルソリューションに貢献します（h2）</h2>'},
{title:"見出し3",image:"template_h3.gif",description:"見出し用テンプレート3です。",html:'<h3 class="in-title">村本建設の微量PCB廃棄物処理トータルソリューション（h3）</h3>'},
{title:"見出し4",image:"template_h4.gif",description:"見出し用テンプレート3です。",html:'<h4 class="in-title"><i class="fa square"></i>村本建設はお客様保管のトータルソリューションに貢献します（h4）</h4>'},
{title:"箇条書き",image:"template_ul.gif",description:"箇条書き用テンプレートです。",html:'<div class="articleblock"><ul class="list-01"><li><i class="fa square"></i><span>弊社ではトランスを中心に、輸送・搬出入作業、充実した技術・作業スタッフにより　最適な作業をご提案します。</span></li><li><i class="fa square"></i><span>産業廃棄物収集運搬の厳しい法規制への対応経験と体制をふまえ、高度な安全管理・遵法体制でサービスをご提供します。</span></li><li><i class="fa square"></i><span>弊社の作業従事者は、大半が「危険物取扱乙種」資格保有者で、すべての作業員は「PCB廃棄物収集運搬業作業従事者講習」修了者で構成されており、安全かつローコストな微量PCB廃棄物処理を実現します。</span></li></ul></div>'},
{title:"画像付きテンプレート1",image:"template_27.gif",description:"右側に画像を入れ込めるテンプレートです。",html:'<div class="float_img_box float_imgR_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div>'},
{title:"画像付きテンプレート2",image:"template_28.gif",description:"左側に画像を入れ込めるテンプレートです。",html:'<div class="float_img_box float_imgL_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div>'},
{title:"テーブル",image:"template_11.gif",description:"テーブル用テンプレート3です。",html:'<div class="articleblock"><table class="table-01" border="0" cellspacing="0" cellpadding="0" style="width:100%;"><tbody><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="even"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr></tbody></table></div>'},
{title:"テンプレート1",image:"template_29.gif",description:"見出し（h2）と文章が入っているテンプレートです。",html:'<div class="frame frame01 articleblock"><h2 class="heading"><em>微量PCB廃棄物処理（h2）</em></h2><div class="articleblock"><p>PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。保管する事業者は平成39年3月までに適正に処分しなければなりません。</p></div></div>'},
{title:"テンプレート2",image:"template_30.gif",description:"見出し（h2）、文章、箇条書き、画像、テーブルが入っているテンプレートです。",html:'<div class="frame frame01 articleblock"><h2 class="heading"><em>微量PCB廃棄物処理（h2）</em></h2><h2 class="in-title">村本建設はお客様保管の微量ＰＣＢ廃棄物処理の<br>トータルソリューションに貢献します（h2）</h2><div class="articleblock"><p>PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。保管する事業者は平成39年3月までに適正に処分しなければなりません。</p></div><div class="articleblock"><ul class="list-01"><li><i class="fa square"></i><span>弊社ではトランスを中心に、輸送・搬出入作業、充実した技術・作業スタッフにより　最適な作業をご提案します。</span></li><li><i class="fa square"></i><span>産業廃棄物収集運搬の厳しい法規制への対応経験と体制をふまえ、高度な安全管理・遵法体制でサービスをご提供します。</span></li><li><i class="fa square"></i><span>弊社の作業従事者は、大半が「危険物取扱乙種」資格保有者で、すべての作業員は「PCB廃棄物収集運搬業作業従事者講習」修了者で構成されており、安全かつローコストな微量PCB廃棄物処理を実現します。</span></li></ul></div><div class="float_img_box float_imgR_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div><div class="float_img_box float_imgL_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div><div class="articleblock"><table class="table-01"><tbody><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="even"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr></tbody></table></div></div>'},
{title:"テンプレート3",image:"template_31.gif",description:"見出し（h2）、見出し（h3））、文章、箇条書き、画像、テーブルが入っているテンプレートです。",html:'<div class="frame frame02 articleblock"><div class="inner"><h2 class="heading"><em>微量PCB廃棄物処理（h2）</em></h2><h3 class="in-title">村本建設はお客様保管の微量ＰＣＢ廃棄物処理の<br>トータルソリューションに貢献します（h3）</h3><div class="articleblock"><p>PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。保管する事業者は平成39年3月までに適正に処分しなければなりません。</p></div><div class="articleblock"><ul class="list-01"><li><i class="fa square"></i><span>弊社ではトランスを中心に、輸送・搬出入作業、充実した技術・作業スタッフにより　最適な作業をご提案します。</span></li><li><i class="fa square"></i><span>産業廃棄物収集運搬の厳しい法規制への対応経験と体制をふまえ、高度な安全管理・遵法体制でサービスをご提供します。</span></li><li><i class="fa square"></i><span>弊社の作業従事者は、大半が「危険物取扱乙種」資格保有者で、すべての作業員は「PCB廃棄物収集運搬業作業従事者講習」修了者で構成されており、安全かつローコストな微量PCB廃棄物処理を実現します。</span></li></ul></div><div class="float_img_box float_imgR_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div><div class="float_img_box float_imgL_box articleblock"><img src="/common/image/dummy.jpg" alt=""><p class="text">PＣＢ（ポリ塩化ビフェニル）は、絶縁性、不燃性などの特性を活用して、トランス、コンデンサといった電気機器用の絶縁油、各種工業における加熱並びに冷却用の熱媒体等に利用されていましたが、毒性が極めて高いことから1972年以降製造・輸入が禁止されました。 ＰＣＢ廃棄物の処理については、現状を踏まえ法律の一部改正により処理期限が延長されました。、保管する事業者は平成39年3月までに適正に処分しなければなりません。平成21年11月の法改正によって、微量ＰＣＢ廃絶縁油収集運搬が可能となり、「特別管理産業廃棄物収集運搬許可」を取得いたしました。受託分析、分類、処理計画など、お客様保管の微量ＰＣＢ廃棄物処理のトータルソリューションに貢献致します。</p></div><div class="articleblock"><table class="table-01"><tbody><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="even"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr><tr class="odd"><th>表タイトル</th><td>こちらに表組みの内容がはいります内容</td><td>こちらに表組み</td></tr></tbody></table></div></div></div>'}
]});

*/

//テンプレートファイルURL
var url  = "/system/js/tmp_thml/";

//loadDataでテンプレートファイルを取得して変数に代入します。
//ファイル名は適宜変更してください。
//テンプレートを追加する場合はここにファイル名を追加してください。
var tpl01 = loadData(url + "1.html");
var tpl02 = loadData(url + "2.html");
var tpl03 = loadData(url + "3.html");
var tpl04 = loadData(url + "4.html");

//Ajax同期通信で外部テンプレートを読み込んでデータを返します。
function loadData(url) {
        //XMLHttpRequestオブジェクト初期化
        var getData = new XMLHttpRequest();
        //同期通信リクエスト作成
        getData.open("GET", url, false);
        //リクエスト送信
        getData.send(null);
        //レスポンスデータを取得して値を返す
        return(getData.responseText);
}

//CKEditorの「テンプレート」をクリックしたとき表示される内容をJSON形式で作成
//テンプレートを追加する場合はここにテンプレートの情報を追加してください。
CKEDITOR.addTemplates("default",{
        //サムネイル画像のディレクトリパス
        //デフォルトは「/ckeditor/plugins/templates/templates/images/」
        imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"templates/images/"),
        templates:[{
                title:"見出し1",
                image:"template_h1.gif",
                description:"見出し用テンプレート1です。",
                html:tpl01
        },{
                title:"見出し2",
                image:"template_h2.gif",
                description:"見出し用テンプレート2です。",
                html:tpl02
        },{
                title:"見出し3",
                image:"template_h3.gif",
                description:"見出し用テンプレート3です。",
                html:tpl03
        },{
                title:"テキスト",
                image:"template_07.gif",
                description:"テキスト部分のテンプレートです。",
                html:tpl04
        }]
});