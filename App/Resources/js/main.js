//Add Item to Cart
$("document").ready(function(){
    var form_data = $(this).serialize(); //prepare form data for Ajax post
    var URL = "http://a78135893.tafenowweb.net/home/addToCart";  // url to send request

    $(".cart-form").submit(function(e) { //user clicks form submit button
        e.preventDefault();
        var data = {};
        $.each($(this).serializeArray(), function(k, v){
            data['id'] = v.value; // adds form data to an object
        }); // end foreach loop

        $.ajax({
            type : "POST",
            datatype: "json",
            url: URL,
            data: data,
            success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                $('#cart').html(data.cart);
                $('#start').html(data.start);
                $('#update').html(data.update);
            }
        })
    }); // end of submit function
}); // end of document ready