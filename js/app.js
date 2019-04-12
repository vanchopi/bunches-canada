$(document).ready(function(){
	function weeklyInit(){
        $('.countdown-counter').countdown('2020/10/10').on('update.countdown', function(event) {
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

    // ajax get cart
    function getCart() {
        let cart;

        $.post('/index.php', {task:'get_cart'})
            .done(function (response) {
                if(response){
                    console.log(response);
                }
            });
    }
    getCart();

})

