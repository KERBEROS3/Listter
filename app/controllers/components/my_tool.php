<?php
class MyToolComponent extends Object{

	/*
	// twitter投稿時の情報を渡して140文字に収まるように各部品を丸めて返す関数
	// $status		丸める前のつぶやき全文
	// $task		丸める前のねがい全文
	// $timeline	丸める前のみちのり全文
	*/
	
	var $twitter_limit = 140;
	
	function twitter_post_trim($status,$task,$timline){
		if( ($all_count = mb_strlen($status, Configure::read('App.encoding'))) > $this->twitter_limit ){    //140文字($this->twitter_limit)以上はtwitter用にダイエット
					
						//task(ねがい)とstatus(みちのり)と以外の文字数をカウント
						//$other = mb_strlen(" - 「」は{$this->data['Timeline']['progress']}%に! {$base_url}", Configure::read('App.encoding'));
					
						
						//$count = $count - 138;   //文末に..を追加するので二文字減らして138文字
						
						//taskの文字数
						$count_task = mb_strlen($task, Configure::read('App.encoding'));
						//timelineの文字数
						$count_timeline = mb_strlen($timline, Configure::read('App.encoding'));
						//taskとtimeline以外の文字数
						$other = $all_count - $count_task - $count_timeline;
						
						
						$limit = $this->twitter_limit - $other;   //limitがtaskとtimelin合わせて使える文字数。
						
						//pr('その他の文字数:'.$other);
						//pr('使用出来る文字数:' . $limit);	//現在は93文字
						//pr('ねがいの文字数:' . $count_task);
						//pr('<hr />みちのりの文字数:' . $count_timeline);
						
						//ねがいが25文字以上なら20文字にトリミング
						if($count_task > 25){
							$min_task = mb_substr($task,'0',20,Configure::read('App.encoding')) . '…';
							//var_dump(mb_strlen($min_task, Configure::read('App.encoding')));
							$limit = $limit - mb_strlen($min_task, Configure::read('App.encoding'));
						}else{
							$min_task = $task;
							//pr("ねがいの制限後の文字数:" . mb_strlen($min_task, Configure::read('App.encoding')));
							$limit = $limit - mb_strlen($min_task, Configure::read('App.encoding'));
							//var_dump($limit);
						}
						
						// …を追加するので、 $limit - 1 の文字数にみちのりを切り落とす
						$min_comment = mb_substr($timline,'0', $limit - 1 ,Configure::read('App.encoding')) . '…';
						//pr("みちのりの制限後の文字数:" . mb_strlen($min_comment, Configure::read('App.encoding')));
						
						//pr($count);
						//$count = mb_strlen($this->viewVars['tasks']["$task_id"], Configure::read('App.encoding')) - $count; //task(ねがい)
						//pr($count);
						//$min_task = mb_strimwidth($this->viewVars['tasks']["$task_id"],'0',$count,'..',Configure::read('App.encoding'));
						//$min_task = mb_substr($this->viewVars['tasks']["$task_id"],'0',$limit,Configure::read('App.encoding'));
						//$status = "{$this->data['Timeline']['comment']} - 「{$min_task}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
						
						//$status = "{$this->data['Timeline']['comment']} - 「{$min_task}…」は{$this->data['Timeline']['progress']}%に! {$base_url}";
						//var_dump(mb_strlen($status, Configure::read('App.encoding')));
						
						
						//$status = "{$min_comment} - 「{$min_task}」は{$this->data['Timeline']['progress']}%に! {$base_url}";
						return array('min_task'=>$min_task,'min_comment'=>$min_comment);
						
		}else{
			//140文字($twitter_limit)以下ならfalse
			return false;
		}
	}
}

?>