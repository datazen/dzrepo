
var widthClassOptions = [];
var widthClassOptions = ({
		bestseller       : 'bestseller_default_width',
		featured         : 'featured_default_width',
		special          : 'special_default_width',
		latest           : 'latest_default_width',
		related          : 'related_default_width',
		additional       : 'additional_default_width',
		module           : 'module_default_width',
		tabbestseller    : 'tabbestseller_default_width',
		tabfeatured      : 'tabfeatured_default_width',
		tabspecial       : 'tabspecial_default_width',
		tablatest        : 'tablatest_default_width',
		testimonial		 : 'testimonial_default_width'

});





function productCarouselAutoSet() {

	$("#content .product-carousel").each(function() {
		var objectID = $(this).attr('id');

		var myObject = objectID.replace('-carousel','');
		if(myObject.indexOf("-") >= 0)
			myObject = myObject.substring(0,myObject.indexOf("-"));
		if(widthClassOptions[myObject])
			var myDefClass = widthClassOptions[myObject];
		else
			var myDefClass= 'grid_default_width';
		var slider = $("#content #" + objectID + ",   .banners-slider-carousel #" + objectID + ", .homepage-testimonials-inner #" + objectID);
		slider.sliderCarousel({
			defWidthClss : myDefClass,
			subElement   : '.slider-item',
			subClass     : 'product-block',
			firstClass   : 'first_item_tm',
			lastClass    : 'last_item_tm',
			slideSpeed : 200,
			paginationSpeed : 800,
			autoPlay : false,
			stopOnHover : false,
			goToFirst : true,
			goToFirstSpeed : 1000,
			goToFirstNav : true,
			pagination : false,
			paginationNumbers: false,
			responsive: true,
			responsiveRefreshRate : 200,
			baseClass : "slider-carousel",
			theme : "slider-theme",
			autoHeight : true
		});

		var nextButton = $(this).parent().find('.next');
		var prevButton = $(this).parent().find('.prev');
		nextButton.click(function(){
			slider.trigger('slider.next');
		})
		prevButton.click(function(){
			slider.trigger('slider.prev');
		})
	});
}
//$(window).load(function(){productCarouselAutoSet();});
$(document).ready(function(){productCarouselAutoSet();});



function productListAutoSet() {
	$("#content .productbox-grid").each(function(){
		var objectID = $(this).attr('id');
		if(objectID.length > 0){
			if(widthClassOptions[objectID.replace('-grid','')])
				var myDefClass= widthClassOptions[objectID.replace('-grid','')];
		}else{
			var myDefClass= 'grid_default_width';
		}
		$(this).smartColumnsRows({
			defWidthClss : myDefClass,
			subElement   : '.product-items',
			subClass     : 'product-block'
		});
	});
}
$(window).load(function(){productListAutoSet();});
$(window).resize(function(){productListAutoSet();});



function HoverWatcher(selector){
	this.hovering = false;
	var self = this;

	this.isHoveringOver = function() {
		return self.hovering;
	}

	$(selector).hover(function() {
		self.hovering = true;
	}, function() {
		self.hovering = false;
	})
}







