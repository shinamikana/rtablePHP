$(()=>{
    $('.content-img').click(function(){
        if($(this).hasClass('openImg')){
            $(this).removeClass('openImg');
            $('.img-view').removeClass('open');
        }else{
            $(this).addClass('openImg');
            $('.img-view').addClass('open');
        }
    });

    $('.imgSum-img').click(function(){
        if($(this).hasClass('openImg')){
            $(this).removeClass('openImg');
            $('.img-view').removeClass('open');
        }else{
            $(this).addClass('openImg');
            $('.img-view').addClass('open');
        }
    });

    $('#adminBtn').click(function(){

     $('.admin').show();
     $('.img-view').addClass('open');

    });

    $('.img-view').click(()=>{
        $('.imgSum-img').removeClass('openImg');
        $('.content-img').removeClass('openImg');
        $('.admin').hide();
        $('.img-view').removeClass('open');
    });

    $('.fa-times').click(()=>{
        $('.admin').hide();
        $('.img-view').removeClass('open');
    });

    $('.post').eq(0).animate({
        opacity:1,
    },500);

    $(window).scroll(function(){
        $('.post').each(function(){
            let position = $(this).offset().top;
            let scroll = $(window).scrollTop();
            let windowHeight = $(window).height();
            if(scroll > position - windowHeight +300){
                $(this).animate({
                    opacity:1,
                },500);
            }
        });
    });

});