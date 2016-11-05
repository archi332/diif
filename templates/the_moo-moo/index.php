<?php
 // no direct access
 defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
 <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700&amp;subset=cyrillic" rel="stylesheet">
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/system/css/system.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/slick.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/slick-theme.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/general.css" type="text/css" />
 <link rel="stylesheet" href="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/css/general_2.css" type="text/css" />
</head>
<body>
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.8&appId=205504043221868";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
  <div class="top-header">
    <div class="wrap">
      <jdoc:include type="modules" name="top-header" />
    </div>
  </div>
  <div class="main-slider">
  	<jdoc:include type="modules" name="main-slider" />
  </div>
  <div class="main-menu">
    <div class="line-menu"></div>
    <div class="wrap">
  		<jdoc:include type="modules" name="main-menu" />
    </div>
  </div>
  <div class="main-content-wrap">
    <div class="wrap clearfix">
    	<aside>
  			<jdoc:include type="modules" name="aside-menu" />
    	</aside>
    	<div class="main-content">
         <jdoc:include type="component" />
      		<div class="main-content_top">
        		<div class="main-content_single-image">
      				<jdoc:include type="modules" name="main-content_single-image" />
        		</div>
        	<div class="wigets-informers">
        		<jdoc:include type="modules" name="wigets-informers" />
        	</div>
      	</div>
      	<div class="main-content_last-news">
      		<jdoc:include type="modules" name="last-news" />
      	</div>
      	<div class="main-content_bottom clearfix">
        	<div class="main-content_categories">
      			<jdoc:include type="modules" name="categories" />
        	</div>
        	<div class="main-content_banners">
                <jdoc:include type="modules" name="banners" />
        	</div>
      	</div>
      </div>
    </div>
  </div>
  <footer>
  	<div class="wrap footer-flex">
      <div class="footer-left">
      	<jdoc:include type="modules" name="footer-left" />
      </div>
      <div class="footer-middle">
      	<jdoc:include type="modules" name="footer-middle" />
      </div>
      <div class="footer-right">
      	<jdoc:include type="modules" name="footer-right" />
      </div>
    </div>
  </footer>
  <script type="text/javascript" src="<?php echo $this->baseurl ;?>/templates/<?php echo $this->template ;?>/javascript/slick.min.js"></script>

</body>
<script>
  //toggle aside sub-menu
  var menuItem = jQuery("aside .menu > li");
  console.log(menuItem.length);
  menuItem.on("click", function(event) {
    event.preventDefault();
    jQuery(this).find(".nav-child").toggle("slow");
  });

  //add farticipant form
  var addButton = jQuery(".header-add");
  var addForm = jQuery(".top-header .fox-container");
  addButton.on("click", function() {
    addForm.toggle("slow");
  });

  //slick popular news
  var slickNews = jQuery(".main-content_single-image .newsflash-horiz");
   slickNews.slick({
  	infinite: true,
  	autoplay: true,
    arrows: false,
    fade: true,
    cssEase: 'linear'
});

  //currency wiget
  jQuery.ajax({
  method: "GET",
  url: "https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5"
})
  .done(function(data) {
    var tbl = document.createElement('table');
    var trh = document.createElement('tr');
    var nameTd = ['','КУПIВЛЯ','ПРОДАЖ'];
    for (var i = 0; i < nameTd.length; i++) {
      var tdName = document.createElement('td');
          tdName.innerHTML = nameTd[i];
           trh.appendChild(tdName);
    };

    tbl.appendChild(trh);

     var frag = document.createDocumentFragment();
      data.forEach(function(item) {
        var tr = document.createElement('tr');
        var tdCcy = document.createElement('td');
        var tdBuy = document.createElement('td');
        var tdSale = document.createElement('td');
        var resBuy = item.buy.slice(0, 5);
        var resSale = item.sale.slice(0, 5);
        tdCcy.innerHTML = item.ccy;
        tdBuy.innerHTML = resBuy;
        tdSale.innerHTML = resSale;
        tr.appendChild(tdCcy);
        tr.appendChild(tdBuy);
        tr.appendChild(tdSale);
        frag.appendChild(tr);
 });
  tbl.appendChild(frag);
  document.querySelector('.currency').appendChild(tbl);
});

//opening almanac sections
jQuery(".almanac-item_years a").on("click", function (event) {
    event.preventDefault();
    var hrefTarget = jQuery(this).attr("href");
    window.open(hrefTarget);
});
</script>
</html>