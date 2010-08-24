<?php
class Task extends AppModel {

	var $name = 'Task';
	var $recursive = 1;
	
	var $validate = array(
		'task' => array(
						'test' => array(
										'rule' => array('minLengthJP', '1'),
										'message' => '1文字以上を入力してください。'
									),
								array(
										'rule' => array('maxLengthJP', '100'),
										'message' => '100文字以下を入力してください。'
									),
								array(
										'rule' => array('space_only'),
										'message' => '空白以外を入力してください。'
									)
		)
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $hasMany = array(
			'Timeline' => array('className' => 'Timeline',
								'foreignKey' => 'task_id',
								'dependent' => true, //アソシエーションされたモデルデータも連動して削除
								'conditions' => '',
								'fields' => '',
								'order' => 'Timeline.modified desc',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Support' => array('className' => 'Support',
								'foreignKey' => 'task_id',
								'dependent' => true, //アソシエーションされたモデルデータも連動して削除
								//'conditions' => array('comment !=' => ''), //コメント表示用にコメントが空でないSupportをアソシエーション
								'conditions' => '', 
								'fields' => '',
								'order' => 'Support.modified desc',
								//'limit' => '3',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
			'Piece' => array('className' => 'Piece',
								'foreignKey' => 'task_id',
								'dependent' => true, //アソシエーションされたモデルデータも連動して削除
								'conditions' => '',
								'fields' => '',
								//'order' => 'Timeline.modified desc',
								'limit' => '',
								'offset' => '',
								'exclusive' => '',
								'finderQuery' => '',
								'counterQuery' => ''
			),
	);

	//modifiedをタイムスタンプにし、現在のタイムスタンプから引く、経過タイムスタンプを取得ご経過時間を返す関数
	function strToElapsed($str = null){
		$diff = time() - strtotime($str);
			
		//var_dump($diff);
		if($diff < 120){											//2 分未満
			//print 'ちょっと前だよ！！';
			//$tasks[$i]['Task']['modified_elapsed'] = '約 1分前';
			return '約 1分前';
		}elseif($diff < (60*60)){									//1 時間未満
			//$tasks[$i]['Task']['modified_elapsed'] = '約' . (int)($diff / 60) . '分前';
			return '約' . round(($diff / 60)) . '分前';
		}elseif($diff < (120*60)){									//  2 時間未満
			return '約 1時間前';	
		}elseif($diff < (24*60*60)){								// 1 日未満
			return '約' . round(($diff / 3600)) . '時間前';	
		}elseif($diff < (7*24*60*60)){								// 1 週間未満
			return '約' . round(($diff / 86400)) . '日前';	
		}elseif($diff < (30*24*60*60)){								// 1 ヶ月未満
			return '約' . round(($diff / 604800)) . '週間前';	
		}elseif($diff < (12*30*24*60*60)){							// 一年未満
			return '約' . round(($diff / (30*24*60*60))) . 'ヶ月前';
		}else{
			$return = '約' . floor(($diff / (12*30*24*60*60))) . '年';
			$month = round($diff % (12*30*24*60*60) / (30*24*60*60));
			
			if($month > 0){
				return $return . $month . 'ヶ月前';
			}else{
				return $return . '前';
			}
		}
	}

}
?>