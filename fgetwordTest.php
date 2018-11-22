<?php
require_once('fgw.class.php');

echo '<pre>';
highlight_string('
<?php 
/*use a callback to filter and sort by number of occurrences ascending*/
print_r(wordsOccurrences(__DIR__,true,function($word){return strlen($word)>4?true:false;}));
?>');
print_r(wordsOccurrences(__DIR__,1,function($word){return strlen($word)>4?true:false;}));

highlight_string('
<?php 
/*not use a callback to filter and sort alphabetically ascending*/
print_r(wordsOccurrences(__DIR__));
?>');
print_r(wordsOccurrences(__DIR__,-1));

highlight_string('
<?php 
/*not use a callback to filter  nor sort,just keep the appearance order*/
print_r(wordsOccurrences(__DIR__));
?>');
print_r(wordsOccurrences(__DIR__));

highlight_string('
<?php 
/*count words in the current directory  and in the current file using a callback to filter*/
echo countWordsInDir(__DIR__,function($word){return strlen($word)>2?true:false;}).\'<br>\';
$handle=fopen(__FILE__,\'r\');
echo countWordsInFile($handle,function($word){return strlen($word)<5?true:false;}).\'<br>\';
?>');
echo countWordsInDir(__DIR__,function($word){return strlen($word)>2?true:false;}).'<br>';
$handle=fopen(__FILE__,'r');
echo countWordsInFile($handle,function($word){return strlen($word)<5?true:false;}).'<br>';


highlight_string('
<?php 
/*the two code below do the same thing yield a word from the current file resource while moving the pointer*/
rewind($handle);
foreach(yieldWordsFromFile($handle) as $word){
	echo $word.\'<br>\';
}
echo ftell($handle).\'<br>\';

rewind($handle);

while($word=fgetword($handle)){
	echo $word.\'<br>\';
}
?>');
rewind($handle);
foreach(yieldWordsFromFile($handle) as $word){
	echo $word.'<br>';
}
echo ftell($handle).'<br>';

rewind($handle);

while($word=fgetword($handle)){
	echo $word.'<br> ';
}
echo ftell($handle);

"don't do it man";
echo '</pre>';


?>
