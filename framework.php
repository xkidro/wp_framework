<?php 
class vc{
	public function cache($k,$time="+1 hour",$v=NULL){
		if($v===NULL){
			$time_c=get_option("cache_".$k."_time",1);
			if(strtotime($time,$time_c)<strtotime("now")){
				return false;
			}
			$res = get_option("cache_".$k,1);
			if(unserialize($v)===NULL){$res=unserialize($res);}
			return $res;
		}else{
			$ov=$v;
			if(is_array($v)){$v=serialize($v);}
			update_option("cache_".$k,$v);
			update_option("cache_".$k."_time",strtotime("now"));
			return $ov;
		}
	}
	public function img($image="",$size="full"){
		if(is_array($image)){
			if($size=="full"){
				return $image['url'];
			}elseif(array_key_exists($size, $image['sizes'])){
				return $image['sizes'][$size];
			}else{
				return $image['url'];
			}
		}else{
			$res=wp_get_attachment_image_src($image,$size);
			return $res[0];
		}
	}
	public function video($video){
		global $wp_embed;
		return $wp_embed->run_shortcode('[embed]'.$video.'[/embed]');
	}
}
