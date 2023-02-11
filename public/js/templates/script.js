$(document).ready(function(){
    $(document).on('focus', ':input', function(){
    	$(this).attr('autocomplete', 'off');
    });
});

function actOnEndtyping($input, onDone = () => {}) {
    console.log("actOnEndtyping");
    //setup before functions
    var typingTimer; //timer identifier
    var doneTypingInterval = 500;

    //on keyup, start the countdown
    $input.on("keyup", function (e) {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => doneTyping(e), doneTypingInterval);
    });

    //on keydown, clear the countdown
    $input.on("keydown", function () {
        clearTimeout(typingTimer);
    });

    //user is "finished typing," do something
    function doneTyping(e) {
        //do something
        onDone(e);
    }
}

function toRupiah(angka) {
    var rupiah = "";
    var angkarev = angka.toString().split("").reverse().join("");
    for (var i = 0; i < angkarev.length; i++)
        if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + ".";
    return rupiah
        .split("", rupiah.length - 1)
        .reverse()
        .join("");
}

// make function to get number only from string
function getNumberFromString(string) {
    return parseInt(string.replace(/[^0-9]/g, "") || 0);
}
