<?php

function fgetword($handle,$filter='')
{
	return fgw::fgetword($handle,$filter);
}
function wordsOccurrences($name,$maxfirst=false,$filter=''){
	return fgw::wordsOccurrences($name,$maxfhttps://github.com/PeterKip/log/irst,$filter);
}
function yieldWordsFromDir($dirname,$filter='',&$file=''){
	return fgw::yieldWordsFromDir($dirname,$filter,$file);
}

function yieldWordsFromFile($handle,$filter=''){
	return fgw::yieldWordsFromFile($handle,$filter);
}

function countWordsInDir($dirname,$filter=''){
	return fgw::countWordsInDir($dirname,$filter);
}

function countWordsInFile($handle,$filter=''){
	return fgw::countWordsInFile($handle,$filter);
}

class fgw{
	public static function  fgetword($handle,$filter='')
	{

		if(!is_resource($handle))
			return false;
		if(get_resource_type($handle)!=='stream')
			return false;
		if(-1===@fseek($handle,ftell($handle))) 
			return false;

		$word='';
		if(!is_callable($filter))
		{
			while(false!==($char=fgetc($handle)))
			{
				
				if(preg_match('#[[:space:],[:punct:]]#',$char)
					&&(!empty($word)||$word=='0')
				  )
				{
					
					if(is_numeric($word))
					{
						$word='';
						continue;
					}
					if(isset($prev)&&$prev==='-'
						&&(is_numeric($t=substr($word,strpos($word,'-')+1))||is_numeric($t[0])||substr_count($t,'-')>0)
					  )
					  
					{
						$word=substr($word,0,strrpos($word,'-'));
						unset($prev);
					}
					elseif(isset($prev)
						&&($word[strlen($word)-1]==="'"
							||$word[strlen($word)-1]==="-"
							||$word[strlen($word)-1]==="_"
						  )
					  )
					{
						$word=substr($word,0,-1);
						unset($prev);
					}
						
					if($char==="'"||$char==="_"||$char==="-")
					{
						$word.=$char;
						$prev=true;
						continue;
					}
					break;
				}
				elseif(preg_match('#[[:space:],[:punct:]]#',$char)&&empty($word))
					continue;
				$word.=$char;
			}
		}
		else{
			while(false!==($char=fgetc($handle)))
			{
				if(preg_match('#[[:space:],[:punct:]]#',$char)
					&&(!empty($word)||$word=='0')
				  )
				{

					if(is_numeric($word))
					{
						$word='';
						continue;
					}
					
					if($char==="'"||$char==="_"||$char==="-")
					{
						$word.=$char;
						$prev=true;
						continue;
					}	

					if(isset($prev)&&$prev==='-'
						&&(is_numeric($t=substr($word,strpos($word,'-')+1))||is_numeric($t[0])||substr_count($t,'-')>0)
					  )
					  
					{
						$word=substr($word,0,strrpos($word,'-'));
						unset($prev);
					}
					elseif(isset($prev)
						&&($word[strlen($word)-1]==="'"
							||$word[strlen($word)-1]==="-"
							||$word[strlen($word)-1]==="_"
						  )
					  )
					{
						$word=substr($word,0,-1);
						unset($prev);
					}
					
					if(call_user_func($filter,$word)===false)
					{
						$word='';
						continue;
					}
					break;
					
				}
				elseif(preg_match('#[[:space:],[:punct:]]#',$char)&&empty($word))
					continue;
			
				$word.=$char;
				$word=trim($word);
			}
		}
		if(preg_match('#-#',$word)&&(is_numeric($t=substr($word,strpos($word,'-')+1))||is_numeric($t[0])||substr_count($t,'-')>1))
				  
		{
			$word=substr($word,0,strpos($word,'-'));
			if(!call_user_func($filter,$word))
			{
				$word='';
			}
		}
		return trim($word,"-_");
	}


	public static function yieldWordsFromFile($handle,$filter='')
	{
		while($word=self::fgetword($handle,$filter))
		{
			yield($word);
		}	
	}


	public static function countWordsInFile($handle,$filter='')
	{
		$i=0;
		foreach(self::yieldWordsFromFile($handle,$filter) as $value)
		{
			$i++;
		}
		return $i;
		
	}


	public static function yieldWordsFromDir($dirname,$filter='',&$file='')
	{
		if(!file_exists($dirname)) return ;
		$dir = new DirectoryIterator($dirname);
		foreach ($dir as $it) 
		{
			if (
				!$it->isDot()
				&&$it->isFile()
			) 
			{
				$handle=fopen($file=$it->getPathname(),'rb');
				while($word=self::fgetword($handle,$filter))
				{
					yield($word);
				}
			}
		}
	}

	public static function countWordsInDir($dirname,$filter='')
	{
		$i=0;
		foreach(self::yieldWordsFromDir($dirname,$filter) as $value)
		{
			$i++;
		}
		return $i;
		
	}
	
	public static function wordsOccurrences($name,$maxfirst=0,$filter=''){
		$array=array();
		if(is_dir($name)&&is_readable($name)){
			$prev=$file='';
			foreach(fgw::yieldWordsFromDir($name,$filter,$file) as $word)
			{
				if(!isset($array[$file][$word])) $array[$file][$word]=1;
				else $array[$file][$word]++;
				if($file!=$prev){
					if(!empty($prev)){
						switch($maxfirst){
							case -1:
								uksort($array[$prev],'strnatcasecmp');
							break;
							case 1:
								array_multisort($array[$prev],SORT_DESC,SORT_NUMERIC);
							break;
							default:
							break;
						}
					}
					$prev=$file;
				}
			}
			if(!empty($prev)){
				switch($maxfirst){
					case -1:
						uksort($array[$prev],'strnatcasecmp');
					break;
					case 1:
						array_multisort($array[$prev],SORT_DESC,SORT_NUMERIC);
					break;
					default:
					break;
				}
			}
		}elseif(is_file($name)&&is_readable($name)){
			$handle=fopen($name,'rb');
			foreach(fgw::yieldWordsFromFile($handle,$filter) as $word){
				if(!isset($array[$name][$word])) $array[$name][$word]=1;
				else $array[$name][$word]++;
			}
				switch($maxfirst){
					case -1:
						uksort($array[$name],'strnatcasecmp');
					break;
					case 1:
						array_multisort($array[$name],SORT_DESC,SORT_NUMERIC);
					break;
					default:
					break;
				}
		}
		return $array;
	}

}