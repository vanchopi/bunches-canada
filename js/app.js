$(document).ready(function(){
	function weeklyInit(){
        $('.countdown-counter').countdown('2020/10/11').on('update.countdown', function(event) {
  			let $this = $(this).html(event.strftime(''
                    + '<div class="counter days"><div>%d</div> <i>days</i> </div><span>&#58;</span>'
                    + '<div class="counter hours"><div>%H</div> <i>hr</i> </div><span>&#58;</span>'
                    + '<div class="counter minutes"><div>%M</div> <i>min</i> </div><span>&#58;</span>'
                    + '<div class="counter sec"><div>%S</div> <i>sec</i></div>'));
        });
    }     
    

    weeklyInit();

    let menu = $('.header-menu__wrapper'),
        el =  $('.header-menu__internal');

    $(document).on('click','.menu-bt', function(){    	
    	menu.addClass('show');
    	el.addClass('show');
    })

    $(document).on('click', '.cross-internal', function(){
    	el.removeClass('show');
    	setTimeout(function() { 
    		menu.removeClass('show');    		 
    	}, 350);
    	
    })

    function checkIfWeekly(){
        if ($('input.hidden.checking').length > 0 ){
            $('.week-sale').addClass('hidden');
        }
    }

    checkIfWeekly();

    // ajax get cart
    function getCart() {
        $.post('/index.php', {task:'get_cart'})
            .done(function (response) {
                if(response){
                    try{
                        let resJson = JSON.parse(response);
                        $('.block-internal .txt').html(resJson.content);
                        $('.string-wrapper.cart .txt i').html(resJson.count);
                    } catch(e){

                    }
                }
            });
    }
    getCart();

     $(document).on('mouseover','.cart-cont .common-cont', function(e){

        let el = $('.block-wrapper');        
        el.addClass('show');    
        $('.cart-cont').addClass('show');        
    });

    //mouseout
    $(document).on('mouseout','.cart-cont .common-cont', function(e){
        $('.block-wrapper').removeClass('show'); 
        $('.cart-cont').removeClass('show');
    });

    $(document).on( 'click', '.icon.mobile-cart', function(){
        let wSize = document.documentElement.clientWidth;
        if(wSize < 750){
            $('.cart-cont').addClass('show');
            $('.block-wrapper').addClass('show');
        }    
    });

    $(document).on('click', '.block-wrapper .cross-internal', function(){
        let wSize = document.documentElement.clientWidth;
        if(wSize < 750){  
          $('.block-wrapper').removeClass('show');  
          $('.cart-cont').removeClass('show');
        }  
    })

    /******************************************************************/
    $(document).on('click','.item-order__wrapp .show-cart', function(){        
        let wSize = document.documentElement.clientWidth;
        if(wSize < 750){
            $(".icon.mobile-cart").trigger("click");
        }else{
            $('body, html').animate({ scrollTop: 0 }, 600);
            $('.block-wrapper').addClass('show');
        }
    })
    /******************************************************************/


    $(document).on('click', '.item-order__wrapp span.cart-class', function () {
        let wrp = $('.item-order__wrapp');        
        let _ = $(this),
            data = _.data();
        $.post('/index.php', data)
            .done(function (response) {
                if(response){                    
                    console.log(response);
                    if (response.indexOf('[--1--]success[--1--]') != -1){
                        $(this).closest(wrp).addClass('get-cart');
                        response = response.replace('[--1--]success[--1--],','');
                    }else{
                        $(this).closest(wrp).children('.message').text('Error');                        
                    }
                    _.after(response);
                    getCart();                                     
                }
            });  
    })




    // data-quantity: 1
    // data-product_id: 2963
    // data-prod_id[]: 2963
    // data-Color:
    // data-input_ribbon_color:
    // data-flypage: shop.flypage.tpl
    // data-manufacturer_id: 1
    // data-category_id: 291
    // data-product_name: Orange Bliss Bridal Bouquet
    // data-Itemid: 479
    // data-product_id: 2963
    // data-set_price[]:
    // data-adjust_price[]:
    // data-master_product[]:
    // data-func: cartAdd
    // data-page: shop.cart
    // data-option: com_virtuemart
    // data-ajax_action: 1
    // data-quantity_per_bunch: 1


})

