$(window).load(function(){

    $("#example-basic").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "fade",
        autoFocus: true,
        enableKeyNavigation: true,
        enablePagination: true,
        enableFinishButton: false,
        showFinishButtonAlways: false,



                onFinished: function (event, currentIndex)
                {
                    //alert("success");
                    $.post('stripe.php', $('#myWizard').serialize(), function( data ) {
                        alert( "Data Loaded: " + data ); // Display information from link_to your_php_file.php
                    });

                }




    });
    $(":checkbox").click(function(){
                    $(this).click(function(){
                        if (this.checked) {
                            $(".xtradetails").show();


                        } else{
                            $(".xtradetails").hide();

                        }
                    })
                })
    $('#submitMpesa').click(function(event) {
    $('#mpesaProcedure').hide();
    $('#succesifulPay').show();
});
    $('select').change(function(){
        var price=$("#price").val();
        var party=$("#partysizee").val();
        var charges=party*price;

        var priceConainer=$('<div/>');
        priceConainer.append("<div> The total charges are: "+charges+"Ksh</div>");
        $('#cost').html(priceConainer);
    });
    function initialize() {

        var mapProp = {
            center:new google.maps.LatLng(51.508742,-0.120850),
            zoom:5,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };
        var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    $('#succesifulPay').hide();
    $("#cardProcedure").hide();
    $("#mpesaProcedure").hide();
    $("#btncard").hide();
    $("#btnmpesa").hide();

    $('.mpesaProcedure').click(function(){
        $(this).find('input[type=radio]').prop('checked', true);
    })
    $('.cardProcedure').click(function(){
        $(this).find('input[type=radio]').prop('checked', true);
    })

    $("input[id='card']").click(function () {
        $("#btncard").show();
        $("#btnmpesa").hide();
        $("#cardProcedure").show();
        $("#mpesaProcedure").hide();
    });
    $("input[id='mpesa']").click(function () {
        $("#btnmpesa").show();
        $("#btncard").hide();
        $("#cardProcedure").hide();
        $("#mpesaProcedure").show();
    });
})

$(document).ready(function(){

    $('select').change(function() {
        if($(this).value!=0||1){

            var num = parseInt($(this).val(), 10)-1;

            var container = $('<div></div>');
            container.append('<h4>Party Details <h4>');
            for(var i = 1; i <= num; i++) {

                container.append('<div class="row"><h5></h5><div class="col-xs-4 col-sm-4 col-md-4 col-lg-4"><input id="id'+i+'" name="namee[]" placeholder="name" class="form-control" /></div> <div id="desc">are you allergic to anything?</div> <label><input type="checkbox" name="allergyCheck" value="" id="yes"></label> <div class="xtradetails"><input type=" text" name="allergyy[]" placeholder="description of allergy" class="form-control">  <input class="form-control" type="text" name="phonee[]" placeholder="Phone" id="">   <input class="form-control" type="text" placeholder="email" name="emaill[]" id=""></div><br>');

                $('#partyDetails').html(container);
                $(".xtradetails").hide();
                
            }

        }
    });

});

$(document).ready(function(){

    $(':checkbox').click(function(){
        if (this.checked) {
            var newDiv = $('<div>contents</div>');

            // you can insert element like this:
            newDiv.insertAfter($(this));

            // or like that (choose syntax that you prefer):
            $(this).after(newDiv);
        } else{
            $(this).next().filter('div').remove();
        }
    })
});

$(document).ready(function(){
    $('div').on('click', function(){
        $('div.selected').removeClass('selected');
        $(this).addClass('selected');
        $(this).children("input[type=radio]").click();

    });
    $("#submitMpesa").click(function(){

        $("#succesifulPay").show().preventDefault();
    });
});



Stripe.setPublishableKey('pk_test_Ka4YnAiHc9YhOmPJDSlYgRQt');

var stripeResponseHandler = function(status, response) {
    var $form = $('#myWizard');

    if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('#submitstripe').prop('disabled', false);
    } else {
        // token contains id, last4, and card type
        var token = response.id;
        // Insert the token into the form so it gets submitted to the server
        $form.append($('<input type="hidden" name="stripeToken" />').val(token));
        // and re-submit
        var element = $(document.activeElement);
        alert(element);
        if(element.hasClass('final')){
            $.post('stripe.php', $('#myWizard').serialize(), function( data ) {
                alert( "Data Loaded: " + data ); // Display information from link_to your_php_file.php
            });
            alert("Submission Successful!");
        }
        else{
            $('#submitstripe').hide();
            $('#cardProcedure').hide();
            $('#succesifulPay').show();
            e.preventDefault();
            return false;
        }



    }
};

jQuery(function($) {
    $('#myWizard').submit(function(e) {
        var $form = $(this);

        // Disable the submit button to prevent repeated clicks
        $form.find('#submitstripe').prop('disabled', true);
        alert("ssubmitting");
        Stripe.card.createToken($form, stripeResponseHandler);

        // Prevent the form from submitting with the default action
        return false;
    });



});
