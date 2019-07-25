function openWindowCenterStd(htmlSource,title) {
	var nWinWidth  = 800;		// ウインドウサイズ幅
	var nWinHeight = 800;	// ウインドウサイズ高
	var sStyle = "";
	return openWinCenter(nWinWidth, nWinHeight, htmlSource, title, sStyle);
}

function openWinCenter(pnWidth, pnHeight, psLocation, psName, psStyle) {
	var sStyle = 'width=' + pnWidth;
		sStyle += ',';
		sStyle += 'height=' + pnHeight;
		sStyle += ',';
		sStyle += "toolbar=no,";		// ツールバー有無
		sStyle += "location=no,";		// ロケーションフィールド有無
		sStyle += "directories=no,";	// ディレクトリボタン有無
		sStyle += "status=yes,";		// ステータスバー有無
		sStyle += "menubar=no,";		// メニューバー有無
		sStyle += "scrollbars=yes,";	// スクロールバー有無
		sStyle += "resizable=yes,";		// リサイズ可不可
	var nWidth = (screen.width / 2) - (pnWidth / 2);

	var nHeight = (screen.height / 2) - (pnHeight / 2);
	if (navigator.userAgent.indexOf('MSIE') != -1) {
		sStyle += 'left=' + nWidth;
		sStyle +=',';
		sStyle += 'top=' + nHeight;
		sStyle +=',';
		sStyle += "toolbar=no,";		// ツールバー有無
		sStyle += "location=no,";		// ロケーションフィールド有無
		sStyle += "directories=no,";	// ディレクトリボタン有無
		sStyle += "status=yes,";		// ステータスバー有無
		sStyle += "menubar=no,";		// メニューバー有無
		sStyle += "scrollbars=yes,";	// スクロールバー有無
		sStyle += "resizable=yes,";		// リサイズ可不可
		sStyle += psStyle;
	}else if(navigator.userAgent.indexOf('Mozilla') != -1) {
		sStyle += 'screenX=' + nWidth;
		sStyle += ',';
		sStyle += 'screenY=' + nHeight;
		sStyle += "toolbar=no,";		// ツールバー有無
		sStyle += "location=no,";		// ロケーションフィールド有無
		sStyle += "directories=no,";	// ディレクトリボタン有無
		sStyle += "status=yes,";		// ステータスバー有無
		sStyle += "menubar=no,";		// メニューバー有無
		sStyle += "scrollbars=yes,";	// スクロールバー有無
		sStyle += "resizable=yes,";		// リサイズ可不可
	}
	oWin = window.open(psLocation, psName, sStyle);
}