$(document).ready(function(){
    fn_btnScroll();
    fn_calendar();

    fn_products();


})

function fn_btnScroll(){
    var btn = $('#button');
    $(window).scroll(function () 
    {
      if ($(window).scrollTop() > 300) 
      {
        btn.addClass('show');
      } 
      else {
        btn.removeClass('show');
      }
    });

    btn.on('click', function (e) 
    {
      e.preventDefault();
      $('html, body').animate({ scrollTop: 0 }, '300');
    });
}

function fn_calendar(){
    calendar = new CalendarYvv("#calendar", moment().format("Y-M-D"), "Sunday");
    calendar.funcPer = function(ev){
      console.log(ev.currentSelected)
      // alert(ev.currentSelected);
      location.href = "./6Wheel_D11.html?d="+ev.currentSelected ;
    };
    
    // preselected dates
    var d = new Date().getDate();
    calendar.diasResal = [d]

    // background color of preselected dates
    calendar.colorResal = "#28a7454d"

    // text color of preselected dates
    calendar.textResalt = "#28a745"

    // background class
    calendar.bg = "bg-light";

    // text color class
    calendar.textColor = "text-black";

    // class for normal buttons
    calendar.btnH = "btn-outline-black";

    // button class when hovering over
    calendar.btnD = "btn-rounded-success";

    calendar.createCalendar();
}

function fn_products(){
    $.ajax({
        type: "post",
        url: "http://web.spiderfeed.alpha/th/service/api_mobilepanich_product.php",
        data: {
          "cmd": "products",
        },
        dataType: "json",
        beforeSend: function(){
          // $('#sign_out').prop('disabled', true);
        },
        complete: function(){
          // $('#sign_out').prop('disabled', false);
        },
        success: function(res) {
            var status  = res['status'];
            var data    = res['data'];
            if (status == true) {
                var productsHTML  = "";
                $.each(data, function( key, value ) {
                  productsHTML += '<h1>' + key + '</h1>';
                  productsHTML += '<div class="row">';
                  $.each(value, function( k, v ) {
                    productsHTML += '<div class="col-lg-4 col-6 mb-1">';
                    productsHTML += '<img class="w-100 img-thumbnail" src="image/products/' + v.product_image + '" style="background: #f8f8f8;">';
                    productsHTML += '<h4>' + v.product_name + '</h4>';

                    if (v.product_discount_status == 'Y') {
                      productsHTML += '<h4><span class="text-danger">฿' + v.product_discount_price+ '</span>';
                      productsHTML += ' <span class="text-decoration-line-through">฿' + v.product_price + '</span></h4>';
                    }else{
                      productsHTML += '<h4><span class="text-danger">฿' + v.product_price + '</span></h4>';
                    }
                    
                    productsHTML += '</div>';
                  });
                  productsHTML += '</div>';
                });

                $('#products').html(productsHTML);
            }
        }
    });
}

