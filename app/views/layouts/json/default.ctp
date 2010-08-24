<?php
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
header('Content-Type: application/json');
//header("X-JSON: ". "{$content_for_layout}"); //‚±‚ê—LŒø‚É‚·‚é‚Æ“®ì‚µ‚È‚¢
echo $content_for_layout;
?>