<?php

    // errors
    ini_set("display_errors",1);
    error_reporting(E_ALL);

    require_once $_SERVER['DOCUMENT_ROOT'] .'/weekly/config.php';

    $weekly = new Weekly();

    if( !$weekly->status ){
        $weekly->redirect();
    }


?>


<!DOCTYPE html>
<html lang="ru">
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>One Week only</title>
	
	<meta name="keywords" content="test-task">
	<link rel="stylesheet" href="/weekly/css/style.css">
</head>
<body>
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
                                        <img src="/weekly/images/logo3.svg" alt="">
                                    </div>
                                </a>
								<div class="cart-cont ">
									<!--<div class="icon cart">
										<img src="/weekly/images/cart.svg" alt="">
									</div>-->
									<div class="string-wrapper cart">
										<div class="number">Shopping Cart</div>
                                        <!--
										<div class="txt">
											<i>0 </i>Products |
											<b>$0.00</b>
										</div>
										-->
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
										<div class="icon">
											<img src="/weekly/images/search-m.svg" alt="">
										</div>
										<div class="icon">
											<img src="/weekly/images/phone-m.svg" alt="">
										</div>
										<div class="icon">
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
										<div class="cross-internal">×</div>
									</div>
									<!--<div class="top-line">
										<div>WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;WOW! Merveilleux service, J'ai adoré les fleurs et le service. Je vais vous conseiller ailleur dans le future.</div>
									</div>	-->
									<ul>
										<li>
											<a href="#">HOME</a>
										</li>
										<li>
											<a href="#">WEDDING FLOWERS</a>
										</li>
										<li>
											<a href="#">BULK FLOWERS</a>
										</li>
										<li>
											<a href="#">VALUE PACKAGES</a>
										</li>
										<li>
											<a href="#">SUPPLIES</a>
										</li>
										<li>
											<a href="#">WEDDING FLOWER</a>
										</li>
										<li>
											<a href="#">GALLERY</a>
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
					                    				<a href="#" class="mainlevel">
					                    					<b>About Us</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>Testimonials</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>Press Room</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>FAQ's</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>Delivery Information</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>Wedding &amp; Event Planners</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
					                    					<b>Get a Quote</b>
					                    				</a>
					                    				<a href="#" class="mainlevel">
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
							                    <div class="item-price">&#36;149.99</div>
							                    <div class="item-price__old">&#36;159.99</div>
							                </div>
							                <div class="item-order__wrapp">
							                    <span>Add To Cart</span>							                    
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
	</div>
	<script src="/weekly/assets/js/jquery-3.3.1.min.js"></script>
	<script src="/weekly/assets/js/jquery.countdown-2.2.0/jquery.countdown.min.js"></script>
	<script src="/weekly/js/app.js"></script>
</body>
</html>	