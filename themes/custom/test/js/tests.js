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

                //alert("entro"); alert(newIndex);

                /*if ( newIndex === 1 ) {
                    $('.wizard > .steps ul').addClass('step-2');
                } else {
                    $('.wizard > .steps ul').removeClass('step-2');
                }
                if ( newIndex === 2 ) {
                    $('.wizard > .steps ul').addClass('step-3');
                } else {
                    $('.wizard > .steps ul').removeClass('step-3');
                }
                if ( newIndex === 3 ) {
                    $('.wizard > .steps ul').addClass('step-4');
                } else {
                    $('.wizard > .steps ul').removeClass('step-4');
                }*/

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


    });

})(jQuery);