$(document).ready(function() {

    displayInsertionArticle();
    displayInsertionVille();
    displayInsertionTailleCliche();
    displayModSuppVille();
    displayModSuppTailleCliche();
    insertionVille();
    insertionTailleCliche();
    modificationTailleCliche();
    modificationVille();
});



function displayInsertionArticle(){
    $(".insertionArticle h4").click(function(){
        var divArticle = $(".insertionArticle div");
        var image = $(".insertionArticle h4 span img");

        if(divArticle.css('display') == 'none') {
            divArticle.css('display', 'block');
            image.attr("src", "../img/close.png");
        } else {
            divArticle.css('display', 'none');
            image.attr("src", "../img/open.png");
        }

    });
}

function displayInsertionVille(){
    $(".insertionVille h4").click(function(){
        var divVille = $(".insertionVille div");
        var image = $(".insertionVille h4 span img");

        if(divVille.css('display') == 'none') {
            divVille.css('display', 'block');
            image.attr("src", "../img/close.png");
        } else {
            divVille.css('display', 'none');
            image.attr("src", "../img/open.png");
        }

    });
}

function displayInsertionTailleCliche(){
    $(".insertionTailleCiche h4").click(function(){
        var divTailleCliche = $(".insertionTailleCiche div");
        var image = $(".insertionTailleCiche h4 span img");

        if(divTailleCliche.css('display') == 'none') {
            divTailleCliche.css('display', 'block');
            image.attr("src", "../img/close.png");
        } else {
            divTailleCliche.css('display', 'none');
            image.attr("src", "../img/open.png");
        }

    });
}


function displayModSuppVille(){
    $(".modSuppVille h4").click(function(){
        var divVille = $(".modSuppVille div:first-of-type");
        var image = $(".modSuppVille h4 span img");


        if(divVille.css('display') == 'none') {
            divVille.css('display', 'block');
            image.attr("src", "../img/close.png");
        } else {
            if($(".modificationsDivVille").css('display') == 'block'){
                $(".modificationsDivVille").css('display', 'none');
            }
            divVille.css('display', 'none');
            image.attr("src", "../img/open.png");
        }
    });
}

function displayModSuppTailleCliche(){
    $(".modSuppTailleCliche h4").click(function(){
        var divTailleCliche = $(".modSuppTailleCliche div:first-of-type");
        var image = $(".modSuppTailleCliche h4 span img");

        if(divTailleCliche.css('display') == 'none') {
            divTailleCliche.css('display', 'block');
            image.attr("src", "../img/close.png");
        } else {
            if($(".modificationsDivTailleCliche").css('display') == 'block'){
                $(".modificationsDivTailleCliche").css('display', 'none');
            }
            divTailleCliche.css('display', 'none');
            image.attr("src", "../img/open.png");
        }

    });
}

function insertionVille(){

    $(".insertionVille input").keyup(function(){
        if($(this).attr("name") == 'codepostal'){
            var cp = $(this).val();

            $.ajax({
                url:"../../backend/insertion.php",
                method:"GET",
                data:{cp:cp},
                success:function(data)
                {
                    data = JSON.parse(data);
                    var nom = $(".insertionVille input:first-of-type");
                    var long = $(".insertionVille input:nth-of-type(3)");
                    var lat = $(".insertionVille input:nth-of-type(4)");

                    if(data[0] == 'true'){
                        var ville = data[1][0];
                        var longitude = data[1][2];
                        var latitude = data[1][3];
                        $(".insertionVille button").attr("disabled","disabled");
                        nom.attr("placeholder", ville);
                        long.attr("placeholder", longitude);
                        lat.attr("placeholder", latitude);

                        $(".insertionVille input:not(.insertionVille input:nth-of-type(2))").prop('disabled', true);

                    } else {
                        $(".insertionVille button").removeAttr('disabled');

                        nom.attr("placeholder", "");
                        long.attr("placeholder", "");
                        lat.attr("placeholder", "");

                        $(".insertionVille input:not(.insertionVille input:nth-of-type(2))").prop('disabled', false);
                    }
                }
            });
        }
    });
}


var tailleX = "";
var tailleY = "";

function insertionTailleCliche() {

    $(".insertionTailleCiche input").keyup(function () {

        if($(this).attr("name") == 'largeur'){
            tailleY = $(this).val();
        }

        if($(this).attr("name") == 'longueur'){
            tailleX = $(this).val();
        }

        if(tailleX != "" && tailleY != "") {
            $.ajax({
                url: "../../backend/insertion.php",
                method: "GET",
                data: {tailleX: tailleX, tailleY: tailleY},
                success: function (data) {
                    data = JSON.parse(data);

                    if(data == 'true'){
                        $(".insertionTailleCiche button").attr("disabled","disabled");

                    } else {
                        $(".insertionTailleCiche button").removeAttr('disabled');
                    }
                }
            });
        } else {
            $(".insertionTailleCiche button").removeAttr('disabled');
        }
    });
}

function modificationTailleCliche(){

    $(".modificationTailleCliche").click(function(){
        var div = $(".modificationsDivTailleCliche");

        if(div.css('display') == 'none') {
            $(".modificationTailleCliche img").attr("src", "../img/closeEdit.png");
            div.css('display', 'block');
            var id_taille_cliche = $(".modSuppTailleCliche select option:selected" ).val();

            $.ajax({
                url: "../../backend/loaded/modificationTailleCliche.php",
                method: "GET",
                data: {id_taille_cliche:id_taille_cliche},
                success: function (data) {
                    div.html(data);
                }
            });

        } else {
            $(".modificationTailleCliche img").attr("src", "../img/edit.png");
            div.css('display', 'none');
        }
    });

    $('.modSuppTailleCliche select').on('change', function() {
        var id_taille_cliche = $(".modSuppTailleCliche select option:selected" ).val();

        $.ajax({
            url: "../../backend/loaded/modificationTailleCliche.php",
            method: "GET",
            data: {id_taille_cliche:id_taille_cliche},
            success: function (data) {
                $(".modificationsDivTailleCliche").html(data);
            }
        });
    });
}


function modificationVille(){

    $(".modificationVille").click(function(){
        var div = $(".modificationsDivVille");

        if(div.css('display') == 'none') {
            $(".modificationVille img").attr("src", "../img/closeEdit.png");
            div.css('display', 'block');
            var id_ville = $(".modSuppVille select option:selected" ).val();

            $.ajax({
                url: "../../backend/loaded/modificationVille.php",
                method: "GET",
                data: {id_ville:id_ville},
                success: function (data) {
                    div.html(data);
                }
            });

        } else {
            $(".modificationVille img").attr("src", "../img/edit.png");
            div.css('display', 'none');
        }


    });

    $('.modSuppVille select').on('change', function() {
        var id_ville = $(".modSuppVille select option:selected" ).val();

        console.log(id_ville);
        $.ajax({
            url: "../../backend/loaded/modificationVille.php",
            method: "GET",
            data: {id_ville:id_ville},
            success: function (data) {
                $(".modificationsDivVille").html(data);
            }
        });
    });
}