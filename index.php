<?php

    // errors
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] .'/weekly/config.php';

    $weekly = new Weekly();

    if( !$weekly->status ){
        $weekly->redirect();
    } else {
        if( !isset($_GET['check']) ){
//            $weekly->correctPrices();
        }

        $weekly->products();

    }


?>


<!DOCTYPE html>
<html lang="ru">
<html>
<head>

    <!-- OLD Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({'gtm.start':
                    new Date().getTime(), event: 'gtm.js'});
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MFZJ2ZC');</script>
    <!-- OLD End Google Tag Manager -->

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>One Week only</title>
	
	<meta name="keywords" content="test-task">
	<link rel="shortcut icon" type="image/ico" href="favicon.ico"/>
	<link rel="stylesheet" href="/weekly/css/style.css">

	<!--[if lt IE 11]>
	<link rel="stylesheet" href="css/style-ie.css">
	<![endif]-->

</head>
<body>
    <!-- OLD Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFZJ2ZC" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- OLD End Google Tag Manager (noscript) -->
	<div class="container">
		<section>
			<div class="weekly-cont">

				<div class="weekly-cont__internal">
					<div class="weekly-cont__header">
						<div class="header-top__wrapper">
							<div class="header-top__internal">
								<div class="left-text__wrp">
									<div class="text-cont">
										FOR DELIVERIES OUTSIDE OF THE Canada
									</div>
									<div class="flags usa">
                                        <a href="https://bunchesdirect.com" target="_blank"><img src="/weekly/images/us.svg" alt=""> USA</a>
									</div>
									<div class="flags aus">
                                        <a href="http://bunchesdirect.com.au" target="_blank"><img src="/weekly/images/au.svg" alt=""> AUS</a>
									</div>
								</div>
								<div class="top-search__wrp">
                                    <form action="/index.php?page=shop.browse&amp;option=com_virtuemart&amp;Itemid=663" method="post">
                                        <input type="text" name="keyword">
                                        <input type="submit">
                                    </form>
                                    <div class="search-bt__wrp">
										<div class="search-bt">
											<img src="images/search.svg" alt="">
										</div>
									</div>
								</div>
							</div>	
						</div>
						<div class="header-middle__wrapper">
							<div class="header-middle__internal">
								<div class="phone-cont ">
									<!--<div class="icon phone">
										<img src="/weekly/images/phone.svg" alt="">
									</div>-->
									<a href="tel:+18666908425" class="string-wrapper phone">
										<span class="number">1-866-690-8425</span>
										<span class="txt">Free Call 24 hours</span>
									</a>
								</div>
                                <a href="/">
                                    <div class="logo-cont">
                                        <img src="/weekly/images/logo3.png" alt="">
                                    </div>
                                </a>
								<div class="cart-cont ">
									<!--<div class="icon cart">
										<img src="/weekly/images/cart.svg" alt="">
									</div>-->
									<div class="common-cont">
										<div class="string-wrapper cart">
											<div class="number">Shopping Cart</div>
											<div class="txt">
                                                My Cart: <i>0</i>
											</div>
										</div>	
										<div class="block-wrapper">
											<div class="block-internal">
												<div class="mobile-cross__wrapper">
													<div class="cross-internal" onclick="">×</div>
												</div>
												<div class="txt">Your Cart is empty.</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="header-line__wrapper">
							
						</div>
						<div class="header-menu-mobile__wrapper">
							<!--<div class="top-line">
								<div>WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future.</div>
							</div>	-->
							<div class="header-menu-mobile__internal">
								<div class="menu-bt">
									<div class="wrapper">
										<img src="/weekly/images/menu-bt.svg" alt="">
									</div>	
								</div>
								<div class="bt-group">
									<div class="wrapper">
										<!--<div class="icon">
											<img src="/weekly/images/search-m.svg" alt="">
										</div>-->
										<div class="icon">
											<a href="tel:+18666908425">
												<img src="/weekly/images/phone-m.svg" alt="">
											</a>	
										</div>
										<div class="icon mobile-cart">
											<img src="/weekly/images/cart-m.svg" alt="">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="header-menu__wrapper">
							<div class="header-menu__internal">
								<div class="menu-top">
									<div class="mobile-cross__wrapper">
										<div class="cross-internal" onclick="">×</div>
									</div>
									<!--<div class="top-line">
										<div>WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future.</div>
									</div>	-->
									<ul>
										<li>
											<a href="https://bunchesdirect.ca">HOME</a>
										</li>
										<li>
											<a href="https://bunchesdirect.ca/Wedding-Flowers.html">WEDDING FLOWERS</a>
										</li>
										<li>
											<a href="https://bunchesdirect.ca/Bulk-Flowers.html">BULK FLOWERS</a>
										</li>
										<li>
											<a href="https://bunchesdirect.ca/Value-Packages.html">VALUE PACKAGES</a>
										</li>
										<li>
											<a href="https://bunchesdirect.ca/Supplies.html">SUPPLIES</a>
										</li>
										<li>
											<a href="https://www.weddingwire.ca/wedding-flowers/bunchesdirect--e8654/photos/0">WEDDING FLOWER</a>
										</li>
										<li>
											<a href="https://www.weddingwire.ca/wedding-flowers/bunchesdirect--e8654/photos/0">GALLERY</a>
										</li>
										<li class="week-sale">
											<a href="https://bunchesdirect.ca/weekly/">WEEK SALE</a>
										</li>											
									</ul>
									<div class="menu-bg">
										<img src="/weekly/images/cat_bg.svg" alt="">
									</div>
								</div>
								<div class="menu-bottom">
									<div class="secondary_menu">
					                    <table width="100%" border="0" cellpadding="0" cellspacing="1">
					                    	<tbody>
					                    		<tr>
					                    			<td nowrap="nowrap">
					                    				<a href="https://bunchesdirect.ca/about-us.html" class="mainlevel">
					                    					<b>About Us</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/Testimonials/menu-id-383.html" class="mainlevel">
					                    					<b>Testimonials</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/press-room-bunches-direct-gives-back-helping-operation-shower/menu-id-413.html" class="mainlevel">
					                    					<b>Press Room</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/component/option,com_fsf/Itemid,361/view,faq/" class="mainlevel">
					                    					<b>FAQ's</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/delivery-policy.html" class="mainlevel">
					                    					<b>Delivery Information</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/About-Us/become-an-event-account-member-save-on-bulk-flowers/menu-id-201.html" class="mainlevel">
					                    					<b>Wedding &amp; Event Planners</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/menu-id-202.html?task=quoteform" class="mainlevel">
					                    					<b>Get a Quote</b>
					                    				</a>
					                    				<a href="https://bunchesdirect.ca/About-Us/flower-care-instructions/menu-id-443.html" class="mainlevel">
					                    					<b>Flower Care</b>
					                    				</a>
					                    			</td>
					                    		</tr>
					                    	</tbody>
					                    </table>

					                </div>
								</div>								
							</div>
						</div>
					</div>		
					<div class="weekly-cont__banner">
							<img class="img-large" src="/weekly/images/banner.svg" alt="">
							<img class="img-medium" src="/weekly/images/banner-small.svg" alt="">
						<div class="item-counter">
							<div class="countdown-counter" id="countdown-counter"></div>
						</div>
					</div>
					<div class="weekly-cont__items">
						<div class="items-list__cont">
							<ul class="items-list__warp">

                                <?php foreach( $weekly->products as $product ): ?>
								<li class="item">
							        <div class="item-internal__wrap">
							            <div class="item-internal__cont">
							                <div class="item-counter__wrap">
							                    <div class="item-img__wrap">
							                        <a href="<?php echo $product['url']; ?>">
							                            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
							                        </a>							                        
							                        <div class="item-percantage">
							                            <div class="percents"><?php echo $product['sale']; ?>%</div>
							                            off
							                        </div>
							                    </div>
							                    <div class="item-counter">
							                        <div class="countdown-counter" id="countdown-counter"></div>
							                    </div>
							                </div>
							                <div class="item-title">
							                    <div class="title-wrp">
							                        <a href="<?php echo $product['url']; ?>">
							                        	<span><?php echo $product['name']; ?></span>
							                        </a>
							                    </div>
							                </div>
							                <div class="item-price__wrap">
                                                <div class="item-price">&#36;<?php echo ((float)$product['week_sale_price'] - 0.01); ?></div>
                                                <div class="item-price__old">&#36;<?php echo (float)$product['product_price']; ?></div>
							                </div>
							                <div class="item-order__wrapp">

							                	<div class="message">This product has been added</div>

							                    <span
							                    	class="cart-class"
                                                    data-quantity="1"
                                                    data-product_id="<?php echo $product['product_id']?>"
                                                    data-prod_id="<?php echo $product['product_id']?>"
                                                    data-flypage="shop.flypage.tpl"
                                                    data-manufacturer_id="1"
                                                    data-product_name="<?php echo $product['name']; ?>"
                                                    data-func="cartAdd"
                                                    data-page="shop.cart"
                                                    data-option="com_virtuemart"
                                                    data-ajax_action="1"
                                                    data-quantity_per_bunch="1"
                                                >Add To Cart</span>

                                                <span class="show-cart">Show Cart Now</span>
                                                <div class="hidden-block"></div>							                    

							                </div>
							            </div>
							        </div>
							    </li>
                                <?php endforeach; ?>

							</ul>
						</div>
					</div>
				</div>
			</div>
		</section> 
		<!--<input type="hidden" class="hidden checking">-->
	</div>
	<script src="/weekly/assets/js/jquery-3.3.1.min.js"></script>
	<script src="/weekly/assets/js/jquery.countdown-2.2.0/jquery.countdown.min.js"></script>
	<script src="/weekly/js/app.js"></script>
</body>
</html>	