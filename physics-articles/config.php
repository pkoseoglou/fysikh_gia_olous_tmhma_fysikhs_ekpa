<?php
session_start();
define('SITE_NAME', 'Φυσική για όλους');
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'change-me-now');
define('DATA_DIR', __DIR__ . '/data');
define('UPLOAD_DIR', __DIR__ . '/uploads');
function load_json($file){$p=DATA_DIR.'/'.$file; if(!file_exists($p)) return []; return json_decode(file_get_contents($p),true) ?: [];}
function save_json($file,$data){$p=DATA_DIR.'/'.$file; $tmp=$p.'.tmp'; file_put_contents($tmp,json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT),LOCK_EX); rename($tmp,$p);}
function esc($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function greeklish($s){
    $map=['αι'=>'ai','ει'=>'ei','οι'=>'oi','ου'=>'ou','αυ'=>'av','ευ'=>'ev','Α'=>'a','Ά'=>'a','Β'=>'v','Γ'=>'g','Δ'=>'d','Ε'=>'e','Έ'=>'e','Ζ'=>'z','Η'=>'i','Ή'=>'i','Θ'=>'th','Ι'=>'i','Ί'=>'i','Ϊ'=>'i','Κ'=>'k','Λ'=>'l','Μ'=>'m','Ν'=>'n','Ξ'=>'x','Ο'=>'o','Ό'=>'o','Π'=>'p','Ρ'=>'r','Σ'=>'s','Τ'=>'t','Υ'=>'y','Ύ'=>'y','Ϋ'=>'y','Φ'=>'f','Χ'=>'ch','Ψ'=>'ps','Ω'=>'o','Ώ'=>'o','α'=>'a','ά'=>'a','β'=>'v','γ'=>'g','δ'=>'d','ε'=>'e','έ'=>'e','ζ'=>'z','η'=>'i','ή'=>'i','θ'=>'th','ι'=>'i','ί'=>'i','ϊ'=>'i','ΐ'=>'i','κ'=>'k','λ'=>'l','μ'=>'m','ν'=>'n','ξ'=>'x','ο'=>'o','ό'=>'o','π'=>'p','ρ'=>'r','σ'=>'s','ς'=>'s','τ'=>'t','υ'=>'y','ύ'=>'y','ϋ'=>'y','ΰ'=>'y','φ'=>'f','χ'=>'ch','ψ'=>'ps','ω'=>'o','ώ'=>'o'];
    return strtr(mb_strtolower(trim($s),'UTF-8'),$map);
}
function slugify_base($s){$s=greeklish($s);$s=preg_replace('/[^a-z0-9]+/','-',$s);return trim($s,'-');}
function slugify($s){$base=slugify_base($s);return ($base!==''?$base:'article').'-'.substr(bin2hex(random_bytes(3)),0,6);}
function clean_article_html($html){
    $html=html_entity_decode((string)$html, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $html=preg_replace('#<(script|style)[^>]*>.*?</\1>#is','',$html);
    $html=strip_tags($html,'<p><br><strong><b><em><i><h2><h3><ul><ol><li><blockquote><a>');
    $html=preg_replace("/\\s+on[a-z]+\\s*=\\s*(\"[^\"]*\"|\'[^\']*\'|[^\\s>]+)/i",'',$html);
    $html=preg_replace("/href\\s*=\\s*([\"\'])\\s*javascript:[^\"\']*\\1/i",'href="#"',$html);
    if(!preg_match('/<(p|h2|h3|ul|ol|blockquote)\b/i',$html)){$parts=preg_split('/(?:\r?\n){2,}/',trim($html));$parts=array_values(array_filter($parts,fn($p)=>trim($p)!==''));$html=implode('',array_map(fn($p)=>'<p>'.nl2br(trim($p),false).'</p>',$parts));}
    return trim($html);
}
function article_url($a){return 'article.php?id='.urlencode($a['id']??'');}
function related_articles($current,$articles,$limit=3){
    $others=array_values(array_filter($articles,fn($x)=>($x['id']??'')!==($current['id']??'')));
    usort($others,fn($x,$y)=>(strtotime($y['date']??'1970-01-01')<=>strtotime($x['date']??'1970-01-01')) ?: strcmp($y['id']??'',$x['id']??''));
    $same=array_values(array_filter($others,fn($x)=>($x['sector']??'')===($current['sector']??'')));
    $chosen=[];
    if($same) $chosen[]=$same[0];
    foreach($others as $x){if(count($chosen)>=$limit)break;if(!in_array($x['id']??'',array_column($chosen,'id'),true))$chosen[]=$x;}
    return array_slice($chosen,0,$limit);
}
function logged_in(){return !empty($_SESSION['admin']);}
?>
