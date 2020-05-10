
function CopyToClipboard(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select().createTextRange();
        document.execCommand("copy");

    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().addRange(range);
        document.execCommand("copy");
        //alert("text copied, copy in the text-area")
    }
}


(function ($) {

    'use strict';

    $(function () { 

        jQuery(document).ready(function($){

            $('#wizard').show();

            var url = Drupal.url('utiles_ajax');


            $("a#buyPremium").click( function(){

                var idPremium = $(this).data("id");
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"buyPremium",premiumId:idPremium},
                    success: function(msg){

                        console.log(msg);

                        $('.container-mensajes').show();
                        $('.mensajes').html(msg.text);

                        if(msg.success == true){
                            window.location.href = Drupal.url('user/premios');
                        }

                    },
                    error: function(msg){

                        $('.container-mensajes').show();
                        $('.mensajes').html(msg.text);
                        console.log(msg);

                    }
                });

            });

            //Like
            $("a.upvote-button").click( function(){

                var testId = $('input[name=testId]').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"saveLike",testId:testId,like:"true"},
                    success: function(msg){
                        $('.upvote-button .value').html(msg.cantLike);
                        $('.downvote-button .value').html(msg.cantNolike);
                        $('.vote-button').addClass('disabled');
                    },
                    error: function(msg){
                        $('.vote-button').addClass('disabled');
                    }
                });

            });

            //No like
            $("a.downvote-button").click( function(){

                var testId = $('input[name=testId]').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"saveLike",testId:testId,like:"false"},
                    success: function(msg){
                        $('.upvote-button .value').html(msg.cantLike);
                        $('.downvote-button .value').html(msg.cantNolike);
                        $('.vote-button').addClass('disabled');
                    },
                    error: function(msg){
                        $('.vote-button').addClass('disabled');
                    }
                });

            });


            $("a.actionnext").click( function(){

                var testId = $('input[name=testId]').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"getNews",testId:testId},
                    success: function(msg){
                        if(msg.success){
                            $("#block-newsblock").html(msg.html);
                        }
                    },
                    error: function(msg){
                        console.log(msg);

                    }
                });

            });

            $("a#generarUrlReferido").click( function(){

                var uiduser = $('input[name=uiduser]').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"generateToken",uiduser:uiduser},
                    success: function(msg){
                        //window.location.href = Drupal.url('user') +"/"+ uiduser;

                        var urlReferido = drupalSettings.admin_tests.base_url + "referred/" + msg.token;
                        var urlWhasap = "https://api.whatsapp.com/send?text=" + urlReferido;

                        $("#urlcopy").text(urlReferido);
                        $("#enviarurlreferidowhasap").attr("href",urlWhasap);

                        $(".container-url-generada").show();
                        $(".container-url-sin-generada").hide();

                        $('html, body').animate({
                            scrollTop: $("#infoUrlReferido").offset().top - 100
                        }, 1000);

                    },
                    error: function(msg){
                        console.log(msg);

                    }
                });

            });


            $("a.actionprevious").click( function(){

                var testId = $('input[name=testId]').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"getNews",testId:testId},
                    success: function(msg){
                        if(msg.success){
                            $("#block-newsblock").html(msg.html);
                        }
                    },
                    error: function(msg){
                        console.log(msg);

                    }
                });

            });

            $("a.actionfinish").click( function() {

                var dataRespuestas = [];
                var testId = $('input[name=testId]').val();

                $("input[name^='questionsRadios']:checked").each(function (i, el) {

                    var obj = {question:$(this).data("question"),answer:$(this).val()};
                    dataRespuestas.push(obj);

                });

                console.log(dataRespuestas);

                console.log(drupalSettings.user.uid);

                $('a.actionfinish').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Procesando').addClass('disabled');

                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {op:"saveTest",testId:testId,data:dataRespuestas},
                        success: function(msg){
                            if(msg.uid){

                                if(msg.success){
                                    window.location.href = Drupal.url('test-completado') +"/"+ testId;
                                }else{
                                    window.location.href = Drupal.url('test-fallado') +"/"+ testId;
                                }

                            }else{
                                $("#block-tests-content").html(msg.htmlAnonimo);
                            }

                            $('html, body').animate({
                                scrollTop: 0
                            }, 1000);
                        },
                        error: function(msg){
                            console.log(msg);

                        }
                    });
            });


            $('#enviarsugerenciawhasap').on('click', function(event){
                event.preventDefault();

                var mensaje = $("textarea[name=menssage]").val();
                window.open('https://api.whatsapp.com/send?phone=34667034669&text=Hola ' + mensaje, '_blank'); //window.location.href = 'https://api.whatsapp.com/send?phone=34667034669&text=Hola ' + mensaje;

            });


           $('#form-sugerencias').on('submit', function(event){
               event.preventDefault();

                $.ajax({
                    url: url,
                    dataType: "json",
                    data:$(this).serialize(),
                    method:"POST",
                    beforeSend:function()
                    {
                        $('#enviarsugerencia').attr('disabled','disabled');
                    },
                    error:function(data){
                        console.log(data);
                    },
                    success:function(data)
                    {
                        $('#enviarsugerencia').attr('disabled', false);
                        if(data.success)
                        {
                            $('#form-sugerencias')[0].reset();
                            $('#captcha_error').text('');
                            $('#name_error').text('');
                            $('#mail_error').text('');
                            $('#message_error').text('');
                            grecaptcha.reset();

                            $('#info_alert').text("Sugerencia enviada");

                            return true;
                        }
                        else
                        {
                            $('#name_error').text(data.infoNombre);
                            $('#mail_error').text(data.infoMail);
                            $('#message_error').text(data.infoMenssage);
                            $('#captcha_error').text(data.infocaptcha);
                            $('#info_alert').text("");
                            return false;
                        }
                    }
                })
            });

            if($("#infoDesglose").length){
                $('html, body').animate({
                    scrollTop: $("#infoDesglose").offset().top - 100
                }, 1000);
            }

            if($("#infotests").length){
                $('html, body').animate({
                    scrollTop: $("#infotests").offset().top - 100
                }, 1000);
            }

            if($("#infomisreferidos").length){
                $('html, body').animate({
                    scrollTop: $("#infomisreferidos").offset().top - 100
                }, 1000);
            }

            if($("#infosolicitudes").length){
                $('html, body').animate({
                    scrollTop: $("#infosolicitudes").offset().top - 100
                }, 1000);
            }

            //if($("#infourlcopy").length){

            //
            //    $()
            //}
            //$("a.generarUrlReferido").click( function() {
            //$('#generarUrlReferido').on('click', function(event){
            //    alert("ssss");
            //
            //});


            /***** Tests Imagenes *****/

            $('#nextTestImagenes').on('click', function () {

                console.log($('input[name=idPActual]').val());

                var idPActual = eval($('input[name=idPActual]').val()) + 1;

                console.log(idPActual);

                $('.container-pregunta').each(function(){
                    $(this).hide();
                });

                $("div#" + idPActual).show();

                $('input[name=idPActual]').val(idPActual);

                showhideControles(idPActual);

                $('html, body').animate({
                    scrollTop: $(".namepregunta").offset().top + 200
                }, 1000);
            });

            $('#previosTestImagenes').on('click', function () {

                var idPActual = eval($('input[name=idPActual]').val()) - 1;
                $('input[name=idPActual]').val(idPActual);

                $('.container-pregunta').each(function(){
                    $(this).hide();
                });

                $("div#" + idPActual).show();

                showhideControles(idPActual);

                $('html, body').animate({
                    scrollTop: $(".namepregunta").offset().top + 200
                }, 1000);
            });


            $(".select-mask-img").click( function() {

                var img = $(this).siblings('img.img-fluid');
                clickImgTest($(img));
            });


            $("button#finalizarTestImagenes").click( function() {

                //console.log(selecctionImagenesTest);
                var testId = $('input[name=testId]').val();

                $('button#finalizarTestImagenes').html('<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Procesando...').addClass('disabled');

                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {op:"saveTestImagenes",testId:testId,data:selecctionImagenesTest},
                    success: function(msg){
                       console.log(msg);
                        if(msg.success){

                            $(".container-preguntas").hide();
                            $("#resultRespuestas").html(msg.countAnswareCorrect);
                            $(".container-respuestas-tests-imagenes").show();

                            $('html, body').animate({
                                scrollTop: 0
                            }, 1000);
                        }
                    },
                    error: function(msg){
                        console.log(msg);

                    }
                });
            });


            function showhideControles(idPActual) {

                //$("div.controles").hide();
                var idPActual = idPActual + 1;
                var totalAnsware = $('input[name=totalAnsware]').val();
                if(idPActual < totalAnsware){
                    $('#nextTestImagenes').show();
                    $('#finalizarTestImagenes').hide();
                    $('#previosTestImagenes').hide();
                }else{
                    $('#nextTestImagenes').hide();
                    $('#finalizarTestImagenes').hide();
                    $('#previosTestImagenes').show();
                }

                if(idPActual == totalAnsware){
                    $('#finalizarTestImagenes').show();
                }

            }
            /***** FIN Tests Imagenes *****/

        });
    });

})(jQuery);

var selecctionImagenesTest = [];

function clickImgTest(obj) {


    if($(obj).siblings('.select-mask-img').is(":visible")){
        $(obj).siblings('.select-mask-img').hide();
    }else{
        $(obj).siblings('.select-mask-img').show();
    }

    var testId = $('input[name=testId]').val();
    var existElement = selecctionImagenesTest.find(x => x.answer == $(obj).data("answer"));
    if(existElement != null){
        selecctionImagenesTest.splice($.inArray(existElement, selecctionImagenesTest), 1);
    }else{
        var obj = {question:$(obj).data("question"),answer:$(obj).data("answer")};
        selecctionImagenesTest.push(obj);
    }


   //console.log(selecctionImagenesTest);


}
