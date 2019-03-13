<?php
function kc_opp_old_post_promoter () {
	if (kc_opp_update_time()) {
		update_option('kc_opp_last_update', time());
		kc_opp_promote_old_post();
	}
}

function kc_opp_promote_old_post () {
	global $wpdb;
	$omitCats = get_option('kc_opp_omit_cats');
	$ageLimit = get_option('kc_opp_age_limit');
	if (!isset($omitCats)) {
		$omitCats = KCOMITCATS;
	}
	if (!isset($ageLimit)) {
		$ageLimit = KCAGELIMIT;
	}
	$sql = "SELECT ID
            FROM $wpdb->posts
            WHERE post_type = 'post'
                  AND post_status = 'publish'
                  AND post_date < curdate( ) - INTERVAL ".$ageLimit." DAY 
                  ";
    if ($omitCats!='') {
    	$sql = $sql."AND NOT(ID IN (SELECT tr.object_id 
                                    FROM $wpdb->terms  t 
                                          inner join $wpdb->term_taxonomy tax on t.term_id=tax.term_id and tax.taxonomy='category' 
                                          inner join $wpdb->term_relationships tr on tr.term_taxonomy_id=tax.term_taxonomy_id 
                                    WHERE t.term_id IN (".$omitCats.")))";
    }            
    $sql = $sql."            
            ORDER BY RAND() 
            LIMIT 1 ";
	$oldest_post = $wpdb->get_var($sql);   
	if (isset($oldest_post)) {
		kc_opp_update_old_post($oldest_post);
	}
}

function kc_opp_update_old_post($oldest_post) {
	global $wpdb;
	$post = get_post($oldest_post);
	$origPubDate = get_post_meta($oldest_post, 'kc_opp_original_pub_date', true); 
	if (!(isset($origPubDate) && $origPubDate!='')) {
	    $sql = "SELECT post_date from ".$wpdb->posts." WHERE ID = '$oldest_post'";
		$origPubDate=$wpdb->get_var($sql);
		add_post_meta($oldest_post, 'kc_opp_original_pub_date', $origPubDate);
		$origPubDate = get_post_meta($oldest_post, 'kc_opp_original_pub_date', true); 
	}
	$kc_opp_pos = get_option('kc_opp_pos');
	if (!isset($kc_opp_pos)) {
		$kc_opp_pos = 0;
	}
	if ($kc_opp_pos==1) {
		$new_time = date('Y-m-d H:i:s');
		$gmt_time = get_gmt_from_date($new_time);
	} else {
		$lastposts = get_posts('numberposts=1&offset=1');
		foreach ($lastposts as $lastpost) {
			$post_date = strtotime($lastpost->post_date);
			$new_time = date('Y-m-d H:i:s',mktime(date("H",$post_date),date("i",$post_date),date("s",$post_date)+1,date("m",$post_date),date("d",$post_date),date("Y",$post_date)));
			$gmt_time = get_gmt_from_date($new_time);
		}
	}
	$sql = "UPDATE $wpdb->posts SET post_date = '$new_time',post_date_gmt = '$gmt_time',post_modified = '$new_time',post_modified_gmt = '$gmt_time' WHERE ID = '$oldest_post'";		
	$wpdb->query($sql);
	if (function_exists('wp_cache_flush')) {
		wp_cache_flush();
	}		
		
	$permalink = get_permalink($oldest_post);
	
	do_action( 'old_post_promoted', $post ); 
}

function kc_opp_the_content($content) {
	global $post;
	$showPub = get_option('kc_opp_show_original_pubdate');
	if (!isset($showPub)) {
		$showPub = 1;
	}
	$givecredit = get_option('kc_opp_give_credit');
	if (!isset($givecredit)) {
		$givecredit = 1;
	}
	$origPubDate = get_post_meta($post->ID, 'kc_opp_original_pub_date', true);
	$dateline = '';
	if (isset($origPubDate) && $origPubDate!='') {
		if ($showPub || $givecredit) {
			$dateline.='<p id="kc_opp"><small>';
			if ($showPub) {
				// $dateline.='Originally posted '.$origPubDate.'. ';
				$dateline.='&nbsp;';
			}
			if ($givecredit) {
				// $dateline.='Republished by  <a href="http://www.blogtrafficexchange.com/">Blog Post Promoter</a>';
				$dateline.='&nbsp;';
			}
			$dateline.='</small></p>';
		}
	}
	$atTop = get_option('kc_opp_at_top');
	if (isset($atTop) && $atTop) {
		$content = $dateline.$content;
	} else {
		$content = $content.$dateline;
	}
	return $content;
}

function kc_opp_update_time () {
	$last = get_option('kc_opp_last_update');		
	$interval = get_option('kc_opp_interval');		
	if (!(isset($interval) && is_numeric($interval))) {
		$interval = KCINTERVAL;
	}
	$slop = get_option('kc_opp_interval_slop');		
	if (!(isset($slop) && is_numeric($slop))) {
		$slop = KCINTERVALSLOP;
	}
	if (false === $last) {
		$ret = 1;
	} else if (is_numeric($last)) { 
		$ret = ( (time() - $last) > ($interval+rand(0,$slop)));
	}
	return $ret;
}
?>