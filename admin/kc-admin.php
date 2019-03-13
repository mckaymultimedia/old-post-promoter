<?php

/*  Copyright 2015  Blog Traffic Exchange



Old Post Promoter is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

 

Old Post Promoter is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See th GNU General Public License for more details.

 

You should have received a copy of the GNU General Public License along with Old Post Promoter. If not, see {License URI}.

*/


function kc_opp_menu_setup() {

add_menu_page (

    'OldPostPromoter',

    'OPP Settings',

    'manage_options',

    'old-post-promoter',

    'kc_opp_admin_form',

    KCICONURL //,'1'

);

}



function kc_opp_optionselected($opValue, $value) {

	if($opValue==$value) {

		return 'selected="selected"';

	}

	return '';

}



function kc_opp_get_options() {

	global $kc_opp_interval;

	$kc_opp_interval = get_option('kc_opp_interval');		

	if (!(isset($kc_opp_interval) && is_numeric($kc_opp_interval))) {

		$kc_opp_interval = 1;

	}	

}



function kc_opp_admin_form() {
	echo('<link rel="stylesheet" href="' . KCCSS . '" type="text/css" media="screen" />');

	$message = null;

	if (!empty($_POST['kc_opp_action'])) {

		if (isset($_POST['kc_opp_interval'])) {

			update_option('kc_opp_interval',$_POST['kc_opp_interval']);

		}

		if (isset($_POST['kc_opp_interval_slop'])) {

			update_option('kc_opp_interval_slop',$_POST['kc_opp_interval_slop']);

		}

		if (isset($_POST['kc_opp_age_limit'])) {

			update_option('kc_opp_age_limit',$_POST['kc_opp_age_limit']);

		}

		if (isset($_POST['kc_opp_show_original_pubdate'])) {

			update_option('kc_opp_show_original_pubdate',$_POST['kc_opp_show_original_pubdate']);

		}

		if (isset($_POST['kc_opp_give_credit'])) {

			update_option('kc_opp_give_credit',$_POST['kc_opp_give_credit']);

		}

		if (isset($_POST['kc_opp_pos'])) {

			update_option('kc_opp_pos',$_POST['kc_opp_pos']);

		}

		if (isset($_POST['kc_opp_at_top'])) {

			update_option('kc_opp_at_top',$_POST['kc_opp_at_top']);

		}

		if (isset($_POST['post_category'])) {

			update_option('kc_opp_omit_cats',implode(',',$_POST['post_category']));

		}

		else {

			update_option('kc_opp_omit_cats','');			

		}

	}

 

	$kc_opp_interval = get_option('kc_opp_interval');

	if (!(isset($kc_opp_interval) && is_numeric($kc_opp_interval))) {

		$kc_opp_interval = KCHOUR1;

	}	

	$kc_opp_interval_slop = get_option('kc_opp_interval_slop');

	if (!(isset($kc_opp_interval_slop) && is_numeric($kc_opp_interval_slop))) {

		$kc_opp_interval_slop = KCHOUR4;

	}	

	$kc_opp_age_limit = get_option('kc_opp_age_limit');

	if (!(isset($kc_opp_age_limit) && is_numeric($kc_opp_age_limit))) {

		$kc_opp_age_limit = KCAGELIMIT;

	}	

	$kc_opp_pos = get_option('kc_opp_pos');

	if (!(isset($kc_opp_pos) && is_numeric($kc_opp_pos))) {

		$kc_opp_pos = 1;

	}	

	$kc_opp_show_original_pubdate = get_option('kc_opp_show_original_pubdate');

	if (!(isset($kc_opp_show_original_pubdate) && is_numeric($kc_opp_show_original_pubdate))) {

		$kc_opp_show_original_pubdate = 1;

	}

	$kc_opp_at_top = get_option('kc_opp_at_top');

	if (!(isset($kc_opp_at_top) && is_numeric($kc_opp_at_top))) {

		$kc_opp_at_top = 1;

	}	

	$kc_opp_omit_cats = get_option('kc_opp_omit_cats');

	if (!isset($kc_opp_omit_cats)) {

		$kc_opp_omit_cats = KCOMITCATS;

	}

	$kc_opp_give_credit = get_option('kc_opp_give_credit');

	if (!isset($kc_opp_give_credit)) {

		$kc_opp_give_credit = 1;

	}

	

?>

<div class="wrap">

	<? if (!empty($_POST['kc_opp_action'])) {

		print('<div id="message" class="updated fade"><p>'.__('OPP settings successfully updated.', 'OldPostPromoter').'</p></div>');

	} ?>

	<div class="kc_head">
	  <h1>Old Post Promoter (OPP) Settings</h1>

		<span>Created by <a href="#"><b>Blog Traffic Exchange</b></a></span>

	</div>

	<div class="kc_head kc_top">

		<form id="kc_opp" name="kc_opp_form" action="<?=get_bloginfo('wpurl')?>/wp-admin/admin.php?page=old-post-promoter" method="post">

			<input type="hidden" name="kc_opp_action" value="kc_opp_update_settings" />

			<table cellpadding="10" cellspacing="0" border="0">

				<tr><td><label for="kc_opp_interval">Minimum Interval between Old Post Promotions</label></td>

					<td><select name="kc_opp_interval" id="kc_opp_interval">

						<option value="<? echo KCMINUTE15;?>" <?=kc_opp_optionselected(KCMINUTE15,$kc_opp_interval);?>><? echo __('15 Minutes', 'old-post-promoter');?></option>

						<option value="<? echo KCMINUTE30;?>" <?=kc_opp_optionselected(KCMINUTE30,$kc_opp_interval);?>><? echo __('30 Minutes', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR1;?>" <?=kc_opp_optionselected(KCHOUR1,$kc_opp_interval);?>><? echo __('1 Hour', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR4;?>" <?=kc_opp_optionselected(KCHOUR4,$kc_opp_interval);?>><? echo __('4 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR6;?>" <?=kc_opp_optionselected(KCHOUR6,$kc_opp_interval);?>><? echo __('6 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR12;?>" <?=kc_opp_optionselected(KCHOUR12,$kc_opp_interval);?>><? echo __('12 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR24;?>" <?=kc_opp_optionselected(KCHOUR24,$kc_opp_interval);?>><? echo __('24 Hours (1 Day)', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR48;?>" <?=kc_opp_optionselected(KCHOUR48,$kc_opp_interval);?>><? echo __('48 Hours (2 Days)', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR72;?>" <?=kc_opp_optionselected(KCHOUR72,$kc_opp_interval);?>><? echo __('72 Hours (3 Days)', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR168;?>" <?=kc_opp_optionselected(KCHOUR168,$kc_opp_interval);?>><? echo __('168 Hours (7 Days)', 'old-post-promoter');?></option>

					</select></td>				

				</tr>

				<tr><td><label for="kc_opp_interval_slop">Randomness Interval<br><small style="color:#999">(Added to minimum interval)</span></label></td>

					<td><select name="kc_opp_interval_slop" id="kc_opp_interval_slop">

						<option value="<? echo KCHOUR1;?>" <?=kc_opp_optionselected(KCHOUR1,$kc_opp_interval_slop);?>><? echo __('1 Hour', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR4;?>" <?=kc_opp_optionselected(KCHOUR4,$kc_opp_interval_slop);?>><? echo __('4 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR6;?>" <?=kc_opp_optionselected(KCHOUR6,$kc_opp_interval_slop);?>><? echo __('6 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR12;?>" <?=kc_opp_optionselected(KCHOUR12,$kc_opp_interval_slop);?>><? echo __('12 Hours', 'old-post-promoter');?></option>

						<option value="<? echo KCHOUR24;?>" <?=kc_opp_optionselected(KCHOUR24,$kc_opp_interval_slop);?>><? echo __('24 Hours (1 Day)', 'old-post-promoter');?></option>

					</select></td>

				</tr>

				<tr><td><label for="kc_opp_age_limit">Post Age before eligible for promotion</label></td>

					<td><select name="kc_opp_age_limit" id="kc_opp_age_limit">

						<option value="30" <?=kc_opp_optionselected(30,$kc_opp_age_limit);?>><? echo __('30 Days', 'old-post-promoter');?></option>

						<option value="60" <?=kc_opp_optionselected(60,$kc_opp_age_limit);?>><? echo __('60 Days', 'old-post-promoter');?></option>

						<option value="90" <?=kc_opp_optionselected(90,$kc_opp_age_limit);?>><? echo __('90 Days', 'old-post-promoter');?></option>

						<option value="120" <?=kc_opp_optionselected(120,$kc_opp_age_limit);?>><? echo __('120 Days', 'old-post-promoter');?></option>

						<option value="240" <?=kc_opp_optionselected(240,$kc_opp_age_limit);?>><? echo __('240 Days', 'old-post-promoter');?></option>

						<option value="365" <?=kc_opp_optionselected(365,$kc_opp_age_limit);?>><? echo __('365 Days', 'old-post-promoter');?></option>

						<option value="730" <?=kc_opp_optionselected(730,$kc_opp_age_limit);?>><? echo __('730 Days', 'old-post-promoter');?></option>					

					</select></td>				

				</tr>

				<tr><td><label for="kc_opp_pos">Promote Post to Position<br><small style="color:#999">(Choosing the 2nd position will leave the most recent post in 1st place)</small></label></td>

					<td><select name="kc_opp_pos" id="kc_opp_pos">

						<option value="1" <?=kc_opp_optionselected(1,$kc_opp_pos);?>><? echo __('1st Position', 'old-post-promoter');?></option>

						<option value="2" <?=kc_opp_optionselected(2,$kc_opp_pos);?>><? echo __('2nd Position', 'old-post-promoter');?></option>

					</select></td>				

				</tr>

				<tr><td><label for="kc_opp_show_original_pubdate">Show Original Publication Date on the Post</label></td>

					<td><select name="kc_opp_show_original_pubdate" id="kc_opp_show_original_pubdate">

						<option value="1" <?=kc_opp_optionselected(1,$kc_opp_show_original_pubdate);?>><? echo __('Yes', 'old-post-promoter');?></option>

						<option value="0" <?=kc_opp_optionselected(0,$kc_opp_show_original_pubdate);?>><? echo __('No', 'old-post-promoter');?></option>

					</select></td>				

				</tr>				

				<tr><td><label for="kc_opp_at_top">Show Original Publication Date at Top of the Post</label></td>

					<td><select name="kc_opp_at_top" id="kc_opp_at_top">

						<option value="1" <?=kc_opp_optionselected(1,$kc_opp_at_top);?>><? echo __('Yes', 'old-post-promoter');?></option>

						<option value="0" <?=kc_opp_optionselected(0,$kc_opp_at_top);?>><? echo __('No', 'old-post-promoter');?></option>

					</select></td>				

				</tr>

				<tr><td valign="top"><label for="kc_opp_omit_cats">Categories to Omit from Promotion</label></td>

					<td><ul><? wp_category_checklist(0, 0, explode(',',$kc_opp_omit_cats)); ?></ul></td>

				</tr>

				<tr><td><label for="kc_opp_give_credit">Give OPP Credit with Link</label></td>

					<td><select name="kc_opp_give_credit" id="kc_opp_give_credit">

						<option value="1" <?=kc_opp_optionselected(1,$kc_opp_give_credit);?>><? echo __('Yes', 'old-post-promoter');?></option>

				  </select></td>				

			  </tr>

				

				<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Update OPP Settings" /></td></tr>

			</table>

		</form>

	</div>

	<div class="kc_head kc_top">

		<h3>Other  <a href="http://www.blogtrafficexchange.com/wordpress-plugins/">Wordpress Plugins</a> Coming up from Blog Traffic Exchange</h3>

		<ol>

		  <li><a href="http://www.blogtrafficexchange.com/related-websites">Related Websites</a></li>

			<li><a href="http://www.blogtrafficexchange.com/related-tweets/">Related Tweets</a></li>

			<li><a href="http://www.blogtrafficexchange.com/wordpress-backup/">Wordpress Backup</a></li>

			<li><a href="http://www.blogtrafficexchange.com/blog-copyright">Blog Copyright</a></li>

			<li><a href="http://www.blogtrafficexchange.com/related-posts">Related Posts</a></li>

	  </ol>

  </div>

</div>

<? } 



function kc_opp_admin_head() {

	//echo('<link rel="stylesheet" href="' . KCCSS . '" type="text/css" media="screen" />');

}

?>