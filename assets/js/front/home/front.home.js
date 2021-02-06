/* flash info */

    if($('#itemsFlashInfo').length) {
        let timer = ($('#itemsFlashInfo')[0].innerText.length + 70) * 0.07;
        $('#itemsFlashInfo >:first-child').css('animation-duration',timer+'s')
    }    

/* acces rapide */

    $(document).ready(function(){
        $(".sliderAccesRapide").slick({
            adaptiveHeight: true,
            /*cssEase: 'ease-in-out',
            easing: 'easeinCubic',*/
            /*respondTo: 'min',*/
            touchThreshold: 10,
            speed: 600,
            slidesToShow: 6,
            slidesToScroll: 6,
            responsive: [
                {
                    breakpoint: 1250,
                    settings: {
                        slidesToShow: 5,
                        slidesToScroll: 5,
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        //arrows:false,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        //arrows:false,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        //arrows:false,
                    }
                }
            ]
        });
    });

    $(document).ready(function(){
        $(".sliderKiosque").slick({
            adaptiveHeight: true,       
            /*cssEase: 'ease-in-out',
            easing: 'easeinCubic',*/
            /*respondTo: 'min',*/
            touchThreshold: 10,
            speed: 600,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        //arrows:false,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        //arrows:false,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        //arrows:false,
                    }
                }
            ]
        });
    });