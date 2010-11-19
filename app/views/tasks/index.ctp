
<?php
	//pr($paginator->params);
	
	//dropdown.cssを設定 おうえんポイントドロップダウン部分で常に必要、並び替えでも使用
	echo $html->css('dropdown-default',null,null,false);
	echo $html->css('dropdown',null,null,false);
	
	$javascript->link('prototype', false);
	$this->addScript($javascript->codeBlock("
		function nextComment(nextDate,task_id){
								//alert(nextDate + '||' + task_id);
								
								new Ajax.Updater( {success:'result'},
										'/supports/nextcomment/', {
											method: 'post',
											//evalJSON : false,
											parameters: 'data[nextDate]=' + nextDate + '&data[task_id]=' + task_id,
											onSuccess : function( response ) {
												var data = response.responseText.evalJSON();
												//alert(data[0].Support.id);
												//console.log(data[0].Support.comment);
												//console.log(data[0].User.username);
												//console.log(data[1].Support.comment);
												
												var f = $$('.next' + task_id);
												//console.log(f[0]);
												
												
												for(var i=0; i<f.length; i++){			//同じタスク分の繰り返し
													
												
													for(var d=0; d<data.length; d++){	//タスク内のコメント分だけ繰り返し
														//console.log(f[i])
														
														//タイムスタンプ - CSSのid用
														var dt = new Date();
														uid = dt.getTime();
														
														new Insertion.Before( f[i] , '<div class=\"support comment\"><span id=\"task'+task_id+'_'+uid+'\" class=\"heart task_id'+task_id+'_'+data[d].Support.supporter_user_id+'\">'+ data[d].Support.points +'</span> <span id=\"task'+task_id+'_user'+data[d].Support.supporter_user_id+'_'+uid+'\" class=\"task'+task_id+'_'+data[d].Support.supporter_user_id+'\">'+ data[d].Support.comment +'</span><span class=\"username\">'+ data[d].User.username +'さん</span> </div>');
														//「つぎのおうえん・・」用の時刻を用意
														lastTime = data[d].Support.comment_modified;
													}
													
													
													//もし最初の配列にnextフラグがあれば次のコメントがある。
													if(typeof data[0].next !='undefined'){
														//「つぎのおうえん・・」のonclick属性を更新
														//console.log(lastTime);
														f[i].setAttribute('onclick', 'nextComment(\"'+lastTime+'\",\"'+task_id+'\");');
													}else{
														f[i].hide(); //nextフラグがなければ、次のコメント無いので隠しちゃう
														
													}
												}
												
											}
										}
								);		
								
							
								//var child = Event.element(evt);
								//alert(  child  );
							
								//alert(  \$F('userName')  );
								
		}
	"));
	
	if(isset($login)){
		
		//tooltip用のjs読み込み
		//$javascript->link('tooltip', false);
		
		
		$javascript->link('jquery', false);
		$javascript->link('jquery.qtip', false);
		
		$this->addScript($javascript->codeBlock("
		
				var \$j = jQuery.noConflict();

				\$j(document).ready(function() {
				   // Match all link elements with href attributes within the content div
					\$j('.clickable').qtip({
						position: {
							//type: 'absolute',
							corner: {
									target: 'topRight',
									tooltip: 'bottomLeft'
								},
							//adjust: { x: -205, y: -23 }
						},
						
						
						content: {
							text: 'ハートをクリックで「おうえんポイント」を贈る',
							title: { text: 'Tips' }
						},
						
						style: { 
							name: 'blue', // Inherit from preset style
							padding: 2,
							'font-size': 12,
							title: {
								'font-size': 10,
								'padding': 0,
								'background-color': '#FFF',
								'padding-left': '5px',
								//'margin-right': '180px',
							},
							border: {
								width: 2,
								radius: 4,
								//color: '#6699CC'
							},
							
							
							tip: { // Now an object instead of a string
								corner: 'bottomLeft', // We declare our corner within the object using the corner sub-option
								//color: '#6699CC',
								size: {
									x: 10, // Be careful that the x and y values refer to coordinates on screen, not height or width.
									y : 8 // Depending on which corner your tooltip is at, x and y could mean either height or width!
								}
							}
							
						},
						
						hide: { 
							effect: { 
								type: 'slide',
								length: 140
							}
						},
						show: { 
							effect: { 
								type: 'slide',
								length: 0
							}
						},
					
				   	});
				});
				
		"));
		

		
		//おうえんポイント用のjsを用意
		//$javascript->link('prototype', false); 
		$i = 0;
		$observe = '';
		//foreach($tasks as $temp){
		while($i < $paginator->params['paging']['Task']['current']){
			$i++;
			//pr($temp);
			//$observe .= "Event.observe( 'count{$i}', 'click', function(e){ajaxReq('/supports/increment/{$temp['Task']['id']}');} );\n";
			//自分のタスクはクリック不可なので、Event.observeは用意しない
			if($tasks[$i-1]['Task']['user_id'] !== $login['User']['id']){
				$observe .= "Event.observe( 'count{$i}', 'click', function(e){ajaxReq('{$tasks[$i-1]['Task']['id']}','count{$i}');} );\n";
			}
			//++$i;
		}
		
		//pr($observe);
		
		$aa = '';
		if(isset($login['User']['username']) && $url_params !== 'public_timeline' && !isset($my_page) ){
			$aa = "Event.observe( 'now-follow', 'click', function(e){switchCss('unfollow');} );";
		}
		
		$this->addScript($javascript->codeBlock(" 
							
							window.onload = function() {
			
								//下の方で宣言しているjsメソッド window.onloadが一つしか書けないのでここに書いてる
								{$aa}
								
			
								//Event.observe( 'count1', 'click', function(e){ajaxReq('/supports/increment/64');} );
								
								//Event.observe( 'count1', 'click', function(e){ajaxReq('/supports/increment/64');} );
								
								//これはPHPの変数で、jsを用意して展開している
								{$observe}

								
								/*
									Event.observe( 'count1', 'click', handler); // イベントを監視
									Event.observe( 'count2', 'click', handler); // イベントを監視 
								*/
							}
							
							
							function switchCss(id) {
										if (Element.hasClassName(id , 'display-none')) {
											 Element.removeClassName(id , 'display-none');
										}else{
											Element.addClassName(id , 'display-none');
										}	
							}
									
							//console.log(eval( \"(\" + request.responseText + \")\" ))
							
							function showResponse(req) {
								//console.log(req); //console.logを残すとコンソールoff時にエラーなので注意
								if(!!req.responseText){ //req.responseTextが何もない場合は無条件でreturn false
									//alert('true');
								}else{
									//alert('false');
									//console.log('req.responseText:'+false);
									return false;
								}
								//console.log(req.responseText);
								eval(\"var ret = \" + req.responseText);
								//ret = eval(request.responseText);
								//alert(ret.login);
								//console.log('login:'+ret.login);
								if(ret.login){
									//console.log('login:'+true);
									return true;
								}else{
									//console.log('login:'+false);
									return false;
								}	
							}
							
							
							/*
								function handler(e) {
									alert('クリックされました');
								}
								
								function handler(event) { 
								    var item = Event.element(event); 
								    alert('クリックしたエレメントのid要素は'+item.id+'です'); 
								} 
							*/
							
							//idはcssのid  おうえんポイントの加算と加算結果を返すメソッド
							function ajaxReq(taskId,id){
								//alert(taskId);
								//alert('/supports/increment/'+taskId);
								//console.log(\"クリックされました!!!\");
								//alert(\"クリックされました\");
								//var msec = (new Date()).getTime();
								
								
								new Ajax.Updater( {success:\"result\"},
									'/supports/increment/'+taskId, {
										method: \"get\",
										//evalJSON : false,
										//parameters: \"cache=\"+msec,
										 onSuccess : function( response ) {
											//alert('true');
											var data = response.responseText.evalJSON();
											//eval(\"var data = \" + response.responseText);
											//alert(data.points);
											//alert(data);
											// response.responseJSONにパーズされた値が格納される。
											//alert(response.responseJSON.value);
											//$(id).innerHTML = data.points; //クリックしたところだけ更新(旧)
											//$(id).innerHTML = '成功';
											
											//同じtask_idの更新
											var f = $$('.task_id'+taskId);
											//alert(f);
											//var s = '';
											for(var i=0; i<f.length; i++){
													//s += f[i].innerHTML + '/';
													f[i].innerHTML = data.points;
											}
											//alert(s); // shows: 'joedoe1/secret/'
											
											//個別おうえんポイント部分の自分のポイントだけ更新
											var f = $$('.task_id'+taskId+'_{$login['User']['id']}');
											for(var i=0; i<f.length; i++){
													//s += f[i].innerHTML + '/';
													f[i].innerHTML = data.supporter_point;
											}
										},
										onFailure:function(httpObj){
											//$('count1').innerHTML = 'エラーで読み込めませんでした';
											//$(id).innerHTML = '失敗';
										}
									}
								);
								
							}
							
							
							
							function ouen_comment(css_id,task_id)
							{
							
								var form_value = \$F(css_id);
								encode_form_value = encodeURIComponent(form_value);
								//alert(form_value + task_id);
								
								
								new Ajax.Updater( {success:'result'},
										'/supports/comment/', {
											method: 'post',
											//evalJSON : false,
											parameters: 'data[Support][comment]=' + encode_form_value + '&data[Support][task_id]=' + task_id,
											onSuccess : function( response ) {
												var data = response.responseText.evalJSON();
												
												//alert(data.errors.comment);
												//console.log(data);
												
												//if(data !== null){
												if(typeof data.errors !='undefined'){
													alert(data.errors.comment);
												}else{
													//console.log(data);
													
													//同じtask_idの更新
													//alert('.task'+task_id+'_{$login['User']['id']}');
													var f = $$('.task'+task_id+'_{$login['User']['id']}');
													//alert(f[0]);
													//console.log(f[0])
													
													//var s = '';
													//alert('test2');
													for(var i=0; i<f.length; i++){
															//s += f[i].innerHTML + '/';
															//alert(f[i]);
															
															//alert('test3');
															//兄弟要素のvalue取得してストックする
															brother = f[i].parentNode.descendants();
															
															//alert(brother);
															
															
															
															//親の親のエレメントを取得
															grand = f[i].parentNode.parentNode;
															//alert(grand);
															
															Element.remove(f[i].parentNode); // ドキュメントから親要素を削除
															//兄弟要素のvalue取得してストックしたものから再定義、idは一意であれば適当で大丈夫。クラスは既にある情報で行けそう
															//alert(brother[0].innerHTML);
															//alert(brother[1].innerHTML);
															//alert(brother[2].innerHTML);
															
															//タイムスタンプ
															var dt = new Date();
															uid = dt.getTime(); 
															//alert(dt.getTime()); 
															
															grand.insert( { top : '<div class=\"support comment\"><span id=\"task'+task_id+'_'+uid+'\" class=\"heart task_id'+task_id+'_{$login['User']['id']}\">'+ data.supporter_point +'</span> <span id=\"task'+task_id+'_user{$login['User']['id']}_'+uid+'\" class=\"task'+task_id+'_{$login['User']['id']}\">'+ form_value.escapeHTML() +'</span><span class=\"username\">'+ brother[2].innerHTML +'</span> </div>' } );
															
															

															
															
															

													}

												}
											}
										}
								);		
								
							
								//var child = Event.element(evt);
								//alert(  child  );
							
								//alert(  \$F('userName')  );
							}
							

		"));
	}
?>


<div class="tasks index">
<!-- <h2><?php __('Tasks');?></h2> -->

<?php //follow関係の表示用CSSの準備
	if(isset($this_user_follow)){
		$follow_display = 'display-none';
		$now_follow_display = FALSE;
	}else{
		$follow_display = FALSE;
		$now_follow_display = 'display-none';
	}
	
?>

<?php echo $this->renderElement('profile-head'); ?>




<?php 	// loginしていて自分のところ($url_user_home === TREU)じゃなければタスク登録を表示しない
		//if( isset($login['User']['username'])&& isset($url_user) && $url_user === $login['User']['username'] && isset($url_params)  ){
		if( isset($login['User']['username'])&& isset($url_user_home) ){		?>
			<div class="tasks form">
			<?php echo $form->create('Task');?>
				<fieldset>
					<legend><?php __('あなたのねがいは？');?></legend>
				<?php
					echo $form->text('task');
					echo $form->hidden('Timeline.0.progress');
					echo $form->hidden('Timeline.0.comment');
				?>
				</fieldset>
			<?php echo $form->end('はじめる');?>
			</div>
			<?php
		}elseif(isset($login['User']['username']) && $url_params !== 'public_timeline' && !isset($my_page) ){     //フォローボタン表示部分
			
			//var_dump($tasks['0']['Task']['id']);
			
			//$javascript->link('prototype', false); 
			
			

			echo $ajax->div("follow",array('class'=>$follow_display));
				echo $ajax->form('/follows/add/' . $page_user['User']['id'],'get',array("complete" => 'if(showResponse(request)){switchCss(\'follow\');switchCss(\'now-follow\');}',"url" => '/follows/add/' . $page_user['User']['id']));
				echo $form->submit("フォローする");
				echo $form->end();
			echo $ajax->divend("follow");
			
			echo $ajax->div("now-follow",array('class'=>$now_follow_display));
				echo '<strong>フォロー中</strong>';
			echo $ajax->divend("now-follow");
				

			echo $ajax->div("unfollow",array('class'=>'display-none'));
				echo $ajax->form('/follows/delete/' . $page_user['User']['id'],'get',array("complete" => 'if(showResponse(request)){switchCss(\'follow\');switchCss(\'unfollow\');switchCss(\'now-follow\');}',"url" => '/follows/delete/' . $page_user['User']['id']));
				echo $form->submit("フォローを解除する");
				echo $form->end();
			echo $ajax->divend("unfollow");

	  } 


?>




<p>
<?php
	echo $paginator->counter(array(
		'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true
	)));
	//ユーザ指定があったらpagenationにユーザ名を渡す
	//var_dump($url_params);
	if(isset($url_params)){
		//$paginator->options(array( 'url' => array('controller'=>$url_params,'action'=>'/') ));
		//$paginator->options(array('url' => array('controller' => $url_params )));
		if($url_params === 'public_timeline'){
			$paginator->options(array('url' => array('controller' => 'tasks', 'action' => 'index', $url_params => TRUE)));
		}elseif(isset($user_timeline) && $user_timeline === TRUE){
			//print 'user_timelin dayo!!!';
			//$paginator->options(array('url' => array('controller' => $url_params )));
			//$paginator->options(array('url' => array("$url_params", 'user_timeline' => TRUE)));
			if(isset($url_user_home)){
				$paginator->options(array('url' => array('controller' => 'tasks', 'action' => 'index', 'url_user' => 'home','user_timeline' => TRUE)));
			}else{
				$paginator->options(array('url' => array('controller' => 'tasks', 'action' => 'index', 'url_user' => $url_user,'user_timeline' => TRUE)));
			}
		}elseif(isset($url_user_home)){
			$paginator->options(array('url' => array('controller' => 'tasks', 'action' => 'index', 'url_user' => 'home')));
		}else{
			//var_dump($url_user);
			$paginator->options(array('url' => array('controller' => 'tasks', 'action' => 'index', 'url_user' => $url_user)));
		}
	}
?>
</p>


<?php // /user_timelineと/public_timline以外でソートを表示する
	//var_dump($user_timeline);
	if( !isset($user_timeline) && ( !isset($url_params) || $url_params !== 'public_timeline') ) { 
		$username = isset($url_user_home) ? 'home' : $url_user ;
		$page = $paginator->counter(array('format' => '%page%')) <= "1" ? 1 : $paginator->counter(array('format' => '%page%'));

?>

		<?php
		if(isset($url_user_home)){		
		?>
		
			<ul class="dropdown dropdown-horizontal completed" id="drop-nav">
				<li class="dir"><?php echo $html->link('殿堂入り',array('url_user'=>$username,'page'=>$page,'sort'=>'Timeline.modified','direction'=>'desc')); ?>
					<ul>
						<li>
							<?php
							echo $html->link('殿堂入り非表示',array('controller'=>'users','action'=>'complete_show_flag','show_flag'=>'0'));
							?>
						</li>
						<li>
							<?php
							echo $html->link('殿堂入りも表示',array('controller'=>'users','action'=>'complete_show_flag','show_flag'=>'1'));
							?>
						</li>
						<li>
							<?php
							echo $html->link('殿堂入りだけ表示',array('controller'=>'users','action'=>'complete_show_flag','show_flag'=>'2'));
							?>
						</li>
					</ul>
				</li>
			</ul>
		
		<?php
		}
		?>
	
		<ul class="dropdown dropdown-horizontal" id="drop-nav">
			<li class="dir"><?php echo $html->link('ならびかえ',array('url_user'=>$username,'page'=>$page,'sort'=>'Timeline.modified','direction'=>'desc')); ?>
				<ul>
					<li>
						<?php
						echo '<div>';
						echo $html->link('▼',array('url_user'=>$username,'sort'=>'Timeline.progress','direction'=>'desc','page'=>"$page"));
						echo '</div><div id="sort-txt">';
						echo ' すすみぐあい ';
						echo '</div><div >';
						echo $html->link('▲',array('url_user'=>$username,'sort'=>'Timeline.progress','direction'=>'asc','page'=>"$page"),array('class'=>'sort_right'));
						echo '</div>';
						?>
					</li>
					<li>
						<?php
						echo '<div>';
						echo $html->link('▼',array('url_user'=>$username,'sort'=>'Task.created','direction'=>'asc','page'=>"$page"));
						echo '</div><div id="sort-txt">';
							echo ' はじめた日 ';
						echo '</div><div >';
						echo $html->link('▲',array('url_user'=>$username,'sort'=>'Task.created','direction'=>'desc','page'=>"$page"),array('class'=>'sort_right'));
						echo '</div>';
						?>
					</li>
					<li>
						<?php
						echo '<div>';
						//echo $html->link('▼',array('url_user'=>$username,'sort'=>'Task.modified','direction'=>'asc','page'=>"$page"));
						echo $html->link('▼',array('url_user'=>$username,'sort'=>'Timeline.modified','direction'=>'asc','page'=>"$page"));
						echo '</div><div id="sort-txt">';
						echo ' こうしんした日 ';
						echo '</div><div>';
						//echo $html->link('▲',array('url_user'=>$username,'sort'=>'Task.modified','direction'=>'desc','page'=>"$page"),array('class'=>'sort_right'));
						echo $html->link('▲',array('url_user'=>$username,'sort'=>'Timeline.modified','direction'=>'desc','page'=>"$page"),array('class'=>'sort_right'));
						echo '</div>';
						?>
					</li>
				</ul>
			</li>
		</ul>
<?php } ?>



</div>

<!-- //新しい表示 -->
<div class="list">
<ol>

<?php
//pr($url_user);
//pr($tasks);

$i = 0;
foreach ($tasks as $task):
//pr($task['User']['username']);

	//個別のタスクのactions表示で、
	//mypageでない、または個別の願いが自分のでない時、コメントと削除のアイコンを表示しないように該当要素のclassにdeleteを設定(CSS用)
	//var_dump($my_page);
	//var_dump($task['User']);
	//var_dump($task['User']['username']);
	//var_dump($url_params);
	//var_dump($url_user);
	
	//if(!isset($my_page) || (isset($task['User']) && $url_user !== $task['User']['username']) ){
	if( ( !isset($my_page) && isset($url_params) && $url_params !== 'public_timeline' ) || (isset($task['User']) && $url_user !== $task['User']['username']) ){
		$css_delete = array('class'=>'hidden');  //htmlヘルパーの第三要素に設定する
		$view_user = isset($task['User']['username']) ? $task['User']['username'] : $url_user ;    //ねがいの履歴用のユーザ名
	}else{
		$css_delete = false;
		$view_user = $url_user;    //ねがいの履歴用のユーザ名
	}

?>
	

	
	<li <?php if($i++ === 0){echo 'class="first_list"';}?> >
	<dl>
		<dt>id</dt>
		<dd class="id">
			<?php echo $task['Task']['id']; ?>
		</dd>
		<dt>ねがい</dt>
		<dd class="dream">

			<?php
				//おうえんpointsが全くなければ0なので設定
				if( !isset($task['Task']['supporter_sum_points']) ){ $task['Task']['supporter_sum_points'] =0; }
				//echo $html->tag('span', $task['Task']['supporter_sum_points'], array('class' => 'heart','id' => "count{$i}"));
			?>
			
			
			<ul class="dropdown dropdown-horizontal" id="support">
				<li class="dir">
					<?php
						//$tooltipJS = false;
						unset($loginUserTask);
						if( isset($login) && $task['Task']['user_id'] !== $login['User']['id'] ){
							$loginUserTask = false;		//ログインしてるユーザのタスクではないときに定義
						}
						
						
						//ハートをクリック出来るかどうかチェック、cssのidを用意する。
						$css_clickable = isset($loginUserTask) ? 'clickable' : false ;
						$css_crown = $task['Task']['completed'] ? 'crown' : false ;
					
						//echo $task['Task']['supporter_sum_points']; 
						echo "<span id=\"count{$i}\" class=\"heart task_id{$task['Task']['id']} {$css_clickable} {$css_crown}\" >";
							echo $task['Task']['supporter_sum_points'];
						echo '</span>';
				
					?>
					
					<!-- おうえんコメント表示部分スタート -->
					<ul>
						
							<li>
								<?php
								//if( isset($login) && $task['Task']['user_id'] !== $login['User']['id'] ){
								if( isset($loginUserTask) ){
									//$loginUserTask = false;		//ログインしてるユーザのタスクじゃないときに定義
									//pr('自分のタスクじゃないよ！');
									?>
									<div>
										おうえんコメント
										<input type="text" id="ouen_comment<?php echo $i ?>" onkeypress="if(event.keyCode==13) { ouen_comment('ouen_comment<?php echo "{$i}','{$task['Task']['id']}'" ?>); }">
										<input type="button" value="おうえん" onclick="ouen_comment('ouen_comment<?php echo "{$i}','{$task['Task']['id']}'" ?>);"> 
									</div>
								<?php
								}
								?>
								<div  class="<?php echo "task_{$task['Task']['id']}" ?>">
									<?php
										unset($support_check); //taskのループ毎に破棄して初期化
										foreach($task['Support'] as $key => $sup){
											
											//pr($sup);
											//まだ全くおうえんしてない場合もコメント更新用に「おうえんdivセット」を用意する。
											if(isset($loginUserTask) && $sup['supporter_user_id'] === $login['User']['id'] ){
												//pr('自分のタスクじゃないしおうえんしてるよ！');
												$support_check = true;
											}
											
											//「次のおうえんコメント」用に最後のコメントの日時を取得
											$nextDate = $sup['comment_modified'];
											$supportUserId = $sup['supporter_user_id'];
											
											$sup['comment'] === '' ? $sup['comment'] = 'おうえんしてますが、まだコメントしてません。' : false ;
											
											echo "<div class=\"support comment\"><span id=\"task{$sup['task_id']}_{$sup['id']}_{$i}\" class=\"heart task_id{$sup['task_id']}_{$sup['User']['id']}\">";
												echo $sup['points'];
											echo "</span>";
											echo "<span id=\"task{$sup['task_id']}_user{$sup['supporter_user_id']}_{$i}\" class=\"task{$sup['task_id']}_{$sup['supporter_user_id']}\">";
												echo h($sup['comment']);
											echo "</span>";
											//echo "<span class=\"username\">{$sup['User']['username']}さん</span></div>";
											echo "<span class=\"username\">";
													echo $html->link($sup['User']['username']."さん",array('controller'=>$sup['User']['username']));
											echo "</span></div>";
											
										}
										
										
										//supportの配列を回して、自分が応援していなかったらダミーのおうえんdivを用意
										//pr(isset($support_check));
										if( !isset($support_check) && isset($login) ){
											//pr('自分は全くおうえんしていないよ!');
											
											echo "<div class=\"support comment\" style=\"display: none;\"><span id=\"task{$task['Task']['id']}_dummy_{$i}\" class=\"heart task_id{$task['Task']['id']}_{$login['User']['id']}\">";
												echo 0;
											echo "</span>";
											echo "<span id=\"task{$task['Task']['id']}_userDummy_{$i}\" class=\"task{$task['Task']['id']}_{$login['User']['id']}\">";
												echo 'コメント無し';
											echo "</span>";
											//echo "<span class=\"username\">{$login['User']['username']}さん</span></div>";
											echo "<span class=\"username\">";
													echo $html->link($login['User']['username']."さん",array('controller'=>$login['User']['username']));
											echo "</span></div>";
											
										}
										
									?>
									<?php 
									if(isset($task['NEXT'])){
									?>
									<input class="next <?php echo "next{$sup['task_id']}" ?>" type="button" value="つぎのおうえん.." onclick="nextComment('<?php echo "{$nextDate}','{$task['Task']['id']}'" ?>);">
									<?php
									}
									?>
								</div>
							</li>
							
							<!-- やることリスト表示部分スタート -->
							<!-- 
							<li id="pieces">
							 -->
								
								<!--ここにやる事リスト追加フォームを設置予定 -->
								<?php
									/*
									//自分のタスクで、かつPiece(やる事)があれば。
									if( !isset($loginUserTask) && $task['Piece'] ){
										echo '<ul>';
										foreach($task['Piece'] as $piece){
											echo "<li>{$piece['comment']}</li>";
										}
										echo '</ul>';
									}
									*/
								?>							
							<!-- 
							</li>
							 -->
							<!-- やることリスト表示部分エンド -->
						
					</ul>
					<!-- おうえんコメント表示部分エンド -->
					

					
				</li>
			</ul>
			

			
			<?php
				
				
				echo $text->autoLinkUrls(h(mb_convert_kana($task['Task']['task'],"s")),array('target'=>'_blank'));
				
			?>
		</dd>
		<?php // /public_timline または /home/timeline であればユーザ名を表示する( /ユーザ名/timelineでは表示しない)
		if( (isset($url_params) && $url_params === 'public_timeline') || isset($user_timeline) && $url_params === 'home/timeline' ){ ?>
		<dt>ユーザ名</dt>
		<dd class="user_name">
			<?php echo $html->link( $task['User']['username'] , array('controller'=>$task['User']['username'])); ?>
		</dd>
		<?php } ?>
		<dt>リストの進捗率</dt>
		<dd class="progress <?php if( isset($url_params) && $url_params === 'public_timeline' ){ echo ' ' . $url_params;} ?>">
			<?php echo isset($task['Timeline']['progress']) ? $task['Timeline']['progress'] : '0' ; ?><span class="percent">%</span>
		</dd>
		<dt>みちのり</dt>
		<dd class="comment">
			<?php //echo isset($task['Timeline']['comment']) ? h($task['Timeline']['comment']) : null ; ?>
			<?php echo $text->autoLinkUrls(h(mb_convert_kana($task['Timeline']['comment'],"s")),array('target'=>'_blank')); ?>
		</dd>
		<dt>ねがい開始からの経過時間</dt>
		<dd class="created_elapsed">
			<?php
				//echo $task['Task']['created_elapsed'];
				echo $html->link($task['Task']['created_elapsed'], 
								array('controller'=>'tasks','action'=>'view', 'task_id'=> $task['Task']['id'], 'url_user'=> $view_user,'page'=> 1),
								array('title'=>$task['Task']['created'])
								)
				//echo $task['Task']['created']; 
			?>
		</dd>
		<dt>前回のねがい更新からの経過時間</dt>
		<dd class="modified_elapsed">
			<?php 
			//$url_user = ($url_user === null) ? $task['User']['username'] : $url_user ;
			$tmp_url_user = ($url_user === null) ? $task['User']['username'] : $url_user ;
			echo $html->link($task['Task']['modified_elapsed'],array('controller' => 'tasks','action' => 'view','url_user'=>$tmp_url_user,'timeline_id'=> $task['Timeline']['id'] ), array('title'=>$task['Task']['modified']) ); ?>
			<?php //echo $task['Task']['modified']; ?>
			<?php //echo date('Y-m-d D H:i:s',strtotime($task['Task']['modified'])); ?>
		</dd>
		<dt>操作</dt>
		<dd class="actions">
			<?php 
			echo $html->link($html->image("pencil.png", array("alt" => __('ねがいを更新',true),"title" => __('ねがいを更新',true))), array('controller'=> 'timelines','action'=>'add',$task['Task']['id']),$css_delete,false,false);
			echo $html->link($html->image("clock.png", array("alt" => __('ねがいの履歴',true),"title" => __('ねがいの履歴',true))), 
									array('controller'=>'tasks','action'=>'view', 'task_id'=> $task['Task']['id'], 'url_user'=> $view_user,'page'=> 1),
									null,false,false);
			//var_dump($task['Task']['completed']);
			if($task['Task']['completed'] === "1" ){
				echo $html->link($html->image("crown_silver_reverse.png", array("alt" => __("ねがいの殿堂入り解除",true) ,"title" => __("ねがいの殿堂入り解除",true))),
					array('controller'=>'tasks','action'=>'completed', 'task_id' => $task['Task']['id'],'complete_flag' =>'0'),
					$css_delete,
					//sprintf(__('[%s]\nAre you sure you want to delete?', true), $task['Task']['task']),
					sprintf(__('この「ねがい」の殿堂入りを解除しますか?\n\n「%s」', true), $task['Task']['task']),
					false
				);
			}else{
				echo $html->link($html->image("crown_silver_monochrome.png", array("alt" => __("ねがいを殿堂入り",true) ,"title" => __("ねがいを殿堂入り",true))),
					array('controller'=>'tasks','action'=>'completed', 'task_id' => $task['Task']['id'],'complete_flag' =>'1'),
					$css_delete,
					//sprintf(__('[%s]\nAre you sure you want to delete?', true), $task['Task']['task']),
					sprintf(__('この「ねがい」を殿堂入りに移動させますか?\n\n「%s」', true), $task['Task']['task']),
					false
				);
			}
			//var_dump($css_delete);
			$css_delete_plus = $css_delete;
			$css_delete_plus['id'] = 'burn';
			echo $html->link($html->image("burn.png", array("alt" => __("ねがいを完全に削除",true) ,"title" => __("ねがいを完全に削除",true))),
									array('action'=>'delete', $task['Task']['id']),
									$css_delete_plus ,
									//sprintf(__('[%s]\nAre you sure you want to delete?', true), $task['Task']['task']),
									sprintf(__('本当にこの「ねがい」を削除して大丈夫ですか?\nいままでの「みちのり」も含めて元には戻せません。\n\n「%s」', true), $task['Task']['task']),
									false
								);
			?>

		</dd>
	</dl>
	</li>
<?php endforeach; ?>
</ol>
</div>
<!-- 新しい表示終了 -->


<div class="paging">
	<?php
		$options['last'] = 1;
		$options['first'] = 1;
		$options['separator'] = null;
		$options['modulus'] = 10;
		//echo $paginator->first('最初',array('after'=>'　'));
		echo $paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));
		//echo $paginator->numbers(array('separator'=>null));
		echo $paginator->numbers($options);
		echo $paginator->next(__('next', true).' >>', array(), null, array('class'=>'disabled'));
		//echo $paginator->last('最後',array('before'=>'　')); 
	?>
</div>
