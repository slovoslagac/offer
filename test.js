var WebServiceCall = function () {
    "use strict";

    function n() {
        try {
            return new XMLHttpRequest
        } catch (n) {
        }
        try {
            return new ActiveXObject("Msxml2.XMLHTTP")
        } catch (t) {
        }
        return null
    }

    return function (t, i, r) {
        var u = n();
        u.open("POST", t + "/" + i, !0);
        u.setRequestHeader("Content-type", "application/json");
        u.setRequestHeader("Accept", "application/json");
        u.send(r)
    }
}()