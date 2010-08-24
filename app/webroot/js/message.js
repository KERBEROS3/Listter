var $j = jQuery.noConflict();

$j(function(){
    // アレ
    var element = $j("#flashMessage");

    // アレを閉じる関数
    var close = function(){ element.slideUp(); };

    // アレをビヨーンと表示
    element.slideDown();
    // アレがクリックされたら閉じる
    element.click(close);
    // 5秒後に勝手に閉じる
    setTimeout(close, 5000);
});