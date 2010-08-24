<?php
/*
  default.thtml design for CakePHP (http://www.cakephp.org)
  ported from http://contenteddesigns.com/ (open source template)
  ported by Shunro Dozono (dozono :@nospam@: gmail.com)
  2006/7/6
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(); ?>
	<title>
		<?php //__('CakePHP: the rapid development php framework:'); ?>
		<?php echo $title_for_layout; ?>
	</title>
	<meta name="description" content="ねがいをそだてます。自分の願いや目標・やりたい事ややるべきことをリスト化し、今後の目標や展望など自分の志向を知り、また知ってもらうことができるWEBサービスです。素敵な「ねがい」を進めている人をフォローし、お互いの健闘を励ましながら「ねがい」に向けて歩んでいくことも可能です。 "/>
	<meta name="keywords" CONTENT="リスッター,りすったー,夢,願い,目標,備忘録">
	<?php
		echo $html->meta('icon');

		echo $html->css('base');
		echo $html->css('extend');

    if ($session->check("Message.flash")) {
      echo $javascript->link('jquery', true);
      echo $javascript->link('message', true);
      //echo 'メッセージがあるよ!!';
    }

		echo $scripts_for_layout;
		
		//Google Analytics
		//echo $javascript->link('ga1', true); //第二引数trueで<script>タグで出力
		//echo $javascript->link('ga2', true);
	?>
	
	<meta name="google-site-verification" content="R5TSvvxvujEZqiGQNCxAz6_A-wSlSUOoza2dJ96UHKo" />
	
</head>
<body>

<div id="header">

<div id="title">
	<?php
		if( isset($login) && $login ){
			echo $html->link( $html->image("logo.png", array('width'=>"160",'height'=>"45","alt" => __('Listter: home',true),"title" => __('Listter: home',true))) , array('controller'=>'home'),null,false,false);
		}else{
			echo $html->link( $html->image("logo.png", array("alt" => __('Listter',true),"title" => __('Listter',true))) , '/',null,false,false);
		}
	?>
</div>

<?php echo $this->renderElement('nav'); ?>


<!--
<div id="slogan">Contented: How a content-filled design feels</div>
-->

</div> <!-- end header -->

<!--
	<div id="path">
		<a href="#">Home</a>
		&nbsp;/&nbsp;
		<a href="#">Section Title</a>
		&nbsp;/&nbsp;
		<a href="#">Subsection Title</a>
		&nbsp;/&nbsp;
		<a href="#">Page Title</a>

	</div>
-->

<div id="maincontent">
			<?php $session->flash(); ?>

			<?php echo $content_for_layout; ?>



</div>


<?php echo $this->renderElement('side-bar'); ?>




<div id="footer">

<div id="copyrightdesign">
&copy; 2009 Listter
</div>

<div id="footercontact">
Created by 
<a href="http://heeha.ws/" target="_blank">KERBEROS</a>
</div>

<!--
	<div id="footercontact">
	<a href="http://heeha.ws/cgi-bin/blog/about/">Contact</a>
	</div>
-->

</div>


<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-10291190-1");
pageTracker._trackPageview();
} catch(err) {}</script>

<?php echo $cakeDebug; ?>



</body>
</html>
