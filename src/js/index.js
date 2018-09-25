$(function() {
    //ページトップへ戻る
    pageTop();

    //ページ内スクロール
    pageScroll();

    //ツールチップ
    $('[data-toggle="tooltip"]').tooltip();
//    $.getJSON(jsonFile, {ts: new Date().getTime()}, function(data) {
//    }).done(function(data, status, xhr) {
//    }).fail(function(xhr, status, error) {
//	});
    //エラーページのみ動作
    if($("#error").length) {
        $("#goBack").on("click", function () {
            window.history.back(-1);
            return false;
        });
    }
    //管理画面ページのみ動作
    if($("#dashboard").length) {
        var dataContents = JSON.parse($("#dataContents").attr("data-contents"));
        var $dashboardTabs = $("#dashboardTabs");
        //タブをクリックしたらactiveを外してクリックした方に付け直す
        $dashboardTabs.find("a").on("click", function() {
            $dashboardTabs.find(".nav-link.active").removeClass("active");
            $(this).addClass("active");
            footerPosition(); //フッタの高さ調整
        });
        //ナビゲーションバーのアカウント設定をクリックしたらタブのアカウント設定を擬似クリックする
        $("#navbarAccount").on("click", function() {
            $("#tabAccount").trigger("click");
        });
        //コンテンツ更新ボタン押した場合
        $(".contentsUpdate_button").on("click", function() {
            //一旦全削除
            $("#contentsUpdateTitle").attr("value", "");
            $("#contentsUpdateContents").text("");
            $("#contentsUpdateKeywords").attr("value", "");
            $("#contentsUpdateID").attr("value", "");
            var id = parseInt($(this).attr("data-contentid")); //ID取得
            var content;
            $.each(dataContents, function(index, value) {
                if(index === id) {
                    content = value;
                }
            });
            $("#contentsUpdateTitle").attr("value", content.ti);
            $("#contentsUpdateContents").text(content.co);
            $("#contentsUpdateKeywords").attr("value", content.kw);
            $("#contentsUpdateID").attr("value", id);
        });
        //コンテンツ削除ボタン押した場合
        $(".contentsDelete_button").on("click", function() {
            var id = parseInt($(this).attr("data-contentid")); //ID取得
            var content;
            $.each(dataContents, function(index, value) {
                if(index === id) {
                    content = value;
                }
            });
            $("#contentsDeleteTitle").attr("value", content.ti);
            $("#contentsDeleteID").attr("value", id);
        });
    }
});

//ロード時とリサイズ時にイベント発生
$(window).on("load resize", function() {
    //フッタの位置をコンテンツの高さに応じて変化させる
    footerPosition();
});

//ページトップへ戻る
function pageTop() {
    var returnPageTop = $(".returnPageTop");

    $(window).on("scroll", function(){
        //スクロール距離が400pxより大きければページトップへ戻るボタンを表示
        var currentPos = $(this).scrollTop();
        if (currentPos > 400) {
            returnPageTop.fadeIn();
        } else {
            returnPageTop.fadeOut();
        }
    });

    //ページトップへスクロールして戻る
    returnPageTop.on("click", function () {
        $("body, html").animate({ scrollTop: 0 }, 1000, "easeInOutCirc");
        return false;
    });
}

//ページ内スクロール
function pageScroll() {
    if($("#index").length) { //トップページの場合のみ動作
        var navbarHeight = parseInt($("#index").attr("data-offset"));
        var $navbar = $("#navbar");
        $navbar.find("a:not(.dropdown-toggle)").on("click", function() {
            var speed = 1000;
            var href = $(this).attr("href");
            var targetID = "";
            if(/^(\.\/|\/)$|^(#)?$/.test(href)) { //hrefの値が「/」「./」「#」「」の場合
                targetID = "html";
            }
            else if(/^(\.\/|\/)#.+/.test(href)) { //hrefの値が「/#HOGE」「./#HOGE」「#HOGE」の場合
                targetID = href.slice(RegExp.$1.length); //正規表現の後方参照により"(\.\/|\/)"をRegExp.$1に格納、その文字列の長さを削除し、「#HOGE」だけの状態にして渡す
            }
            else {
                targetID = href;
            }
            var target = $(targetID);
            var position = target.offset().top - navbarHeight;
            $("body, html").animate({ scrollTop:position }, speed, "easeInOutCirc");
            $navbar.find(".navbar-toggle[data-target=\"#navbarList\"]").click(); //移動したらハンバーガーを折りたたむ
            return false;
        });
    }
}

//フッタの位置をコンテンツの高さに応じて変化させる
function footerPosition() {
    var $footer = $(".footer");
    var footerHeight = $footer.height();
    var $wrapper = $("#wrapper");
    var wrapperHeight = $wrapper.height() + $(".header").height() + $footer.height();
    if(wrapperHeight <= window.innerHeight) {
       $footer.addClass("footerAbsolute");
    }
    else {
        $footer.removeClass("footerAbsolute");
    }
}