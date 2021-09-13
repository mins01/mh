<div class="mh-menu" style="z-index: 10; position: relative;background-color:#f8f8f8;padding:0px;margin-bottom:5px;border-bottom:1px solid #ddd;">
	<div class="menu-for-xs  menu-theme-01" style=" display:flex ">
		<div>
			<a class="menu-misc" href="/" style=""><img
					src="<?=SITE_URI_ASSET_PREFIX?>img/logo.png?t=<?=REFLESH_TIME?>"
					style="max-height: 100%;height:49px;border-radius: 5px;" alt="logo image"></a>
		</div>
		<div style="font-size:20px;line-height:49px;margin:0 10px">
			<? $mr= $menu_tree[0]['child'][1]; ?>
			<a class="" href="<?=html_escape($mr['url'])?>" target="<?=html_escape($mr['mn_a_target'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
		</div>
		<div style="font-size:20px;line-height:49px;margin:0 10px">
			<? $mr= $menu_tree[0]['child'][2]; ?>
			<a class="" href="<?=html_escape($mr['url'])?>" target="<?=html_escape($mr['mn_a_target'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
		</div>
		<div style="flex:1 0 auto;display: flex; align-items: center;justify-content: flex-end;" class="text-right">
			<div class="menu-btn " data-menu-open="#ui-menu-01" style="margin:5px;">
				<div class="menu-icon-hamburger">
					<div></div>
				</div>
			</div>
		</div>
	</div>
	<div class="container-fluid">
		<div class="menu-container menu-container-bg-white menu-theme-01" id="ui-menu-01" data-menu-dismiss style="font-size:16px">
			<div class="menu-for-mobile" style="text-align: right;padding:5px">
				<div class="menu-btn" data-menu-dismiss>
					<div class="menu-icon-close">
						<div></div>
					</div>
				</div>
			</div>
			<ul class="menu-box" style="z-index: 10;" data-menu-dismiss>
				<li class="menu menu-for-sm">
					<a class="menu-misc  " href="/" style=""><img
							src="<?=SITE_URI_ASSET_PREFIX?>img/logo.png?t=<?=REFLESH_TIME?>"
							style="max-height: 100%;height:34px;" alt="logo image"></a>
				</li>
				<?
				if(isset($menu_tree[0]['child'])):
					ui_menu_echo_menu($menu_tree[0]['child']);
				endif;
				?>
				<li class="menu" style=" flex: 1 0 auto; text-align: right; display: flex; align-items: center; justify-content: flex-end;">
					<div class="menu-misc"  data-menu-dismiss>
						<? if(!$logedin):?>
							<button type="button" class="btn btn-success btn-xs"
								onclick="window.open('<?=html_escape(SITE_URI_MEMBER_PREFIX.'join')?>','_self')">회원가입</button>
							<button type="button" class="btn btn-warning btn-xs"
								onclick="window.open('<?=html_escape(SITE_URI_MEMBER_PREFIX.'search_id')?>','_self')">계정
								찾기</button>
							<button type="button" class="btn btn-primary btn-xs"
								onclick="window.open('<?=html_escape(SITE_URI_MEMBER_PREFIX.'login')?>','_self')">로그인</button>
						<? else: ?>
							<a
								href="<?=html_escape(SITE_URI_MEMBER_PREFIX.'user_info')?>"><span
									class="glyphicon glyphicon-user"></span><?=html_escape($login_label)?></a>님 <button
								class="btn btn-info btn-xs"
								onclick="window.open('<?=html_escape(SITE_URI_MEMBER_PREFIX.'logout')?>','_self')">로그아웃</button>
						<? endif;?>
					</div>
				</li>
			</ul>
		</div>
		
	</div>
	
</div>
<div class="container-fluid">
	<div class="mh-breadcrumbs">
		<div class="mh-breadcrumb">
			<a class="" href="<?=html_escape(SITE_URI_PREFIX)?>" target="_self" >HOME</a>
		</div>
	<?
	$ts = array();
	foreach($menu['breadcrumbs'] as $k=>$v):
		if($k==0){continue;}
		$mr = $menu_rows[$v];
	?>
		<div class="mh-breadcrumb">
			<a class="" href="<?=html_escape($mr['url'])?>" target="<?=html_escape($mr['mn_a_target'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
		</div>
	<?
	endforeach;

	?>
	</div>
</div>
<!-- <?
// print_r($menu);
?> -->
<?
function ui_menu_echo_menu($menus){
	foreach($menus as $mr):
		if($mr['mn_hide']!='0'){continue;}
		$class = $mr['active']?'selected':'';
		$hasChild = isset($mr['child'][0]);
	?>
	<li class="menu <?=$class?>">
		<? if($hasChild): ?>
			<a class="menu-label" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
		<? else: ?>
			<a class="menu-label" href="<?=html_escape($mr['url'])?>" target="<?=html_escape($mr['mn_a_target'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
		<? endif; ?>
		
		<?
		// print_r($mr['child']);
		if($hasChild):
			?><ul class="menu-box"><?
			ui_menu_echo_menu($mr['child']);
			?></ul><?
		endif;
		?>
	</li>
	<?
	endforeach;
}
?>


