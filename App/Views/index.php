
<?php if($page === '/' || $page === 'home'): require_once $contents['cta_banner']; endif; ?>

<div class="container mx-auto">
	<?php if($page === '/' || $page === 'home'): require_once $contents['slider']; endif; ?>

	<?php require_once $contents['content']?>
</div>