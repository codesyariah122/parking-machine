

<section id="slider" class="grid grid-cols-1 w-full py-[150px]">
	<div class="col-span-full">
		<div class="swiper mySwiper">
			<div class="swiper-wrapper">
				<?php foreach($sliders as $slider): ?>
					<div class="swiper-slide">
						<img src="<?=$slider['img']?>" class="rounded-lg">
					</div>
				<?php endforeach;?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</div>
</section>