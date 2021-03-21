//require('./bootstrap');

require('./users.js');
require('./commercers.js');
require('./categories.js');
require('./customers.js');
require('./distributors');
require('./commerceCategory.js');
require('./categoryfromCommerces.js');
require('./units');
require('./slider.js');
require('./permits');
require('./restaurant');
require('./Tips.js');
require('./market.js');
require('./delivery_hours');
require('./order');
require('./minShopping');
require('./coupons');
require('./dashboard');

$(".inputImg").on("change", function () {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(".imgUpdate").attr("src", e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
    }
});
