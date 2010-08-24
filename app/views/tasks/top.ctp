<?php
	$html->css('quickflips',null,null,false);
	$javascript->link('jquery', false);
	$javascript->link('quickflip', false);
	//$this->addScript($javascript->codeBlock("alert('Hello! CakePHP.');"));
	$this->addScript($javascript->codeBlock("
		$(function() {
			$('.quickFlip2').quickFlip();
		});
	"));
?>

<div class="intro what">
	<h2 class="question">Listterとは？</h2>
	<div class="quickFlip2">
		<div class="redPanel">
			<!-- <p> Front content here </p> -->
			<p class="quickFlipCta">なに？</p>
			<!-- <p style="margin-top:30px;"><a href="http://jonraasch.com/blog/quickflip-2-jquery-plugin">More About QuickFlip</a></p> -->
		</div>
	
		<div class="blackPanel">
			<!-- <p> Back content here </p> -->

			<p class="quickFlipCta">
				Litterは「あなたのねがいは？」という質問に"夢や目標・やりたい事ややるべきこと"を答えて、あなたのねがいを育てていくサービスです。
			</p>
			<p>そして、そのいくつかのねがいによって、あなたがあなたをもっと知る良いきっかけになるかもしれません。</p>
			<p><a href="/users/add" class="join" id="signup_submit">Listterに登録する</a></p>
		</div>
	</div>
</div>


<div  class="intro why">
	<h2 class="question">なぜ使うの？</h2>
	<div class="quickFlip2">
		<div class="redPanel">
			<!-- <p> Front content here </p> -->
			<p class="quickFlipCta">なんで？</p>
			<!-- <p style="margin-top:30px;"><a href="http://jonraasch.com/blog/quickflip-2-jquery-plugin">More About QuickFlip</a></p> -->
		</div>
	
		<div class="blackPanel">
			<!-- <p> Back content here </p> -->

			<p class="quickFlipCta">日常のねがいを更新するだけでも、家族や友だち、職場の同僚に役立つ情報になるからです。タイミングがよかったときは特に。</p>
			<ul>
				<li><strong>元気にねがいに突き進んでる？</strong>　お母さんたちは知りたがります。</li>
				<li><strong>どんな将来のねがいを抱いているんだろう？</strong>　あなたの同僚と仲良くなるきっかけになるかも。</li>
				<li><strong>レベルアップのためにテニスサークルを作った</strong>　友だちも参加したくなるかも知れません。</li>
			</ul>
		</div>
	</div>
</div>

<div  class="intro how">
	<h2 class="question">どうやって？</h2>
	<div class="quickFlip2">
		<div class="redPanel">
			<!-- <p> Front content here </p> -->
			<p class="quickFlipCta">どう使う？</p>
			<!-- <p style="margin-top:30px;"><a href="http://jonraasch.com/blog/quickflip-2-jquery-plugin">More About QuickFlip</a></p> -->
		</div>
	
		<div class="blackPanel">
			<!-- <p> Back content here </p> -->

			<p class="quickFlipCta">Listterを使えばフォローしている友だちがどんなねがいを抱いているのか分かるので、いつでもつながっていられます。フォローはいつでもやめられます。ねがいを進めたくないときは静かにしていればいいので、Lissterにわずらわされることはありません。たっぷり充電してください。</p>
			<p><strong>Listterはきっとあなたをとりこにします。</strong>そして、身の回りにあふれる夢や目標を整理する特効薬になるでしょう。</p>
		</div>
	</div>
</div>