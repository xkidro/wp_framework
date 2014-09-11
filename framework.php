<?php 
class vc{
	public static function cache($k,$time="+1 hour",$v=NULL){
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
	public static function img($image="",$size="full"){
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
	public static function video($video){
		global $wp_embed;
		return $wp_embed->run_shortcode('[embed]'.$video.'[/embed]');
	}
	public static function base64_image($file){
		if(!function_exists('mime_content_type')) {
		    function mime_content_type($filename) {
		        $mime_types = array(
		            'png' => 'image/png',
		            'jpe' => 'image/jpeg',
		            'jpeg' => 'image/jpeg',
		            'jpg' => 'image/jpeg',
		            'gif' => 'image/gif',
		            'bmp' => 'image/bmp',
		            'ico' => 'image/vnd.microsoft.icon',
		            'tiff' => 'image/tiff',
		            'tif' => 'image/tiff',
		            'svg' => 'image/svg+xml',
		            'svgz' => 'image/svg+xml',
		        );
		        $ext = strtolower(array_pop(explode('.',$filename)));
		        if (array_key_exists($ext, $mime_types)) {
		            return $mime_types[$ext];
		        }
		        elseif (function_exists('finfo_open')) {
		            $finfo = finfo_open(FILEINFO_MIME);
		            $mimetype = finfo_file($finfo, $filename);
		            finfo_close($finfo);
		            return $mimetype;
		        }
		        else {
		            return 'application/octet-stream';
		        }
		    }
		}
		$data = file_get_contents($file);
		$type=mime_content_type($file);
		$base64 = 'data:' . $type . ';base64,' . base64_encode($data);
		return $base64;
	}
}
