/**
 * Created by diony on 22/02/2020.
 */
(function ($) {

    'use strict';

    $(function () {

        $("#wizard").steps({
            headerTag: "h4",
            bodyTag: "section",
            transitionEffect: "fade",
            enableAllSteps: false,
            onStepChanging: function (event, currentIndex, newIndex) {

                $(".pagination li").each(function( index ) {
                    $( this ).removeClass('active');
                });

                $(".pagination li").each(function( index ) {

                    if( $(this).data('value') == newIndex){
                        $( this ).addClass('active');
                    }

                });
                return true;
            },
            labels: {
                finish: "Finalizar",
                next: "Siguiente",
                previous: "Anterior"
            }
        });
        // Custom Button Jquery Steps
        $('.forward').click(function(){
            $("#wizard").steps('next');
        })
        $('.backward').click(function(){
            //$("#wizard").steps('previous');
        })

        //hamburguesa
        $('.first-button').on('click', function () {
            $('.animated-icon1').toggleClass('open');
        });





        /*** Carrusel ****/
        $('.carousel.carousel-multi-item.v-2 .carousel-item').each(function(){
            var next = $(this).next();
            if (!next.length) {
                next = $(this).siblings(':first');
            }
            next.children(':first-child').clone().appendTo($(this));

            for (var i=0;i<4;i++) {
                next=next.next();
                if (!next.length) {
                    next=$(this).siblings(':first');
                }
                next.children(':first-child').clone().appendTo($(this));
            }
        });


        $('.quitarCookies').on('click', function () {
            Drupal.eu_cookie_compliance.changeStatus(0);
        });


    });

})(jQuery);