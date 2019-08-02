var search_values = {};
var page = null;
var sorted = "Article";
var rowNumbers = 250;
var perimetre = 0;

$(document).ready(function() {

    $body = $("body");
    $(document).on({
        ajaxStart: function() { $body.addClass("loading");    },
        ajaxStop: function() { $body.removeClass("loading"); }
    });

    search_values["article"] = "";
    search_values["reference"] = "";
    search_values["sujet"] = "";
    search_values["ville"] = "";
    search_values["date"] = "";
    search_values["index_icono"] = "";
    search_values["index_personnes"] = "";
    search_values["neg_inf"] = "";
    search_values["coul_nb"] = "";
    search_values["taille"] = "";

    load_data(search_values,null, sorted, rowNumbers, perimetre);

    $('#lignesPPage').change(function(){
        rowNumbers = $(this).val();
        load_data(search_values,null, sorted, rowNumbers, perimetre);
    });

    $(document).on('click', '#pagination p', function(){
        var pageName = $(this).attr("id");
        page = pageName.substring(4);
        load_data(search_values,page,sorted, rowNumbers, perimetre);
    });

    $(document).on('click', '.detailsVille', function(){
        var id_cliche = $(this).attr("class");

        if($('.'+id_cliche.substring(19, id_cliche.length)).html() == ''){
            $('.tableVille').html("");
            load_details(id_cliche);
        } else {
            $('.'+id_cliche.substring(19, id_cliche.length)).html("");
        }
    });


    $(document).on('click', '#sort_table th', function(){
        var column_name = $(this).html();
        if (sorted == column_name){

            sorted = sorted+"same";
        } else {

            if(column_name != 'Cliché' && column_name != 'Modifications') {
                sorted = column_name;
            }
        }
        if(column_name != 'Cliché' && column_name != 'Modifications') {
            load_data(search_values,page,sorted, rowNumbers, perimetre);
        }
    });


    $('.search_box').keyup(delay(function (e) {
        var search = $(this).val();

        if($(this).attr('name') == 'search_article'){
            if(search == ''){
                search = null;
            }
            search_values["article"] = search;


        } else if($(this).attr('name') == 'search_reference'){
            search_values["reference"] = search;

        } else if($(this).attr('name') == 'search_sujet') {
            search_values["sujet"] = search;

        } else if($(this).attr('name') == 'search_ville'){
            search_values["ville"] = search;
            perimetre = $('.perimetre_box').val();

        } else if($(this).attr('name') == 'search_index_icono'){
            search_values["index_icono"] = search;

        } else if($(this).attr('name') == 'search_index_personnes'){
            search_values["index_personnes"] = search;
        }

        load_data(search_values, null, sorted, rowNumbers, perimetre);

    }, 200));

    $('.search_box_date').change(function() {
        var search = $(this).val();
        search_values["date"] = search;

        load_data(search_values, null, sorted, rowNumbers, perimetre);
    });

    $('.perimetre_box').change(function(){
        if(search_values["ville"] != '') {
            perimetre = $(this).val();

            load_data(search_values, null, sorted, rowNumbers, perimetre);
        }
    });

    $('.neg_inf_box').change(function(){
        var search = $(this).val();

        if(search != 'tous') {
            search_values["neg_inf"] = search;
        } else {
            search_values["neg_inf"] = "";
        }

        load_data(search_values, null, sorted, rowNumbers, perimetre);
    });

    $('.coul_nb_box').change(function(){
        var search = $(this).val();
        if(search != 'tous') {
            search_values["coul_nb"] = search;
        } else {
            search_values["coul_nb"] = "";
        }

        load_data(search_values, null, sorted, rowNumbers, perimetre);
    });

    $('.taille_cliche_box').change(function(){
        var search = $(this).val();
        if(search != 'tous') {
            search_values["taille"] = search;
        } else {
            search_values["taille"] = "";
        }

        load_data(search_values, null, sorted, rowNumbers, perimetre);
    });




});

var positionXVille = null;
var positionYVille = null;
var activePopUpVille = null;


function closePopUpVilleCliche(){
    $(".popupTextVille").hide();
    $(".popupTextCliche").hide();
}

function popUpVilleEnter(e) {
        var classe = $('.popupTextVille' + arguments[1]);
        if (activePopUpVille != '.popupTextVille' + arguments[1]) {
            $(".popupTextVille").hide();
        }

        classe.toggle();
        activePopUpVille = '.popupTextVille' + arguments[1];

        var p = classe.position();
        positionXVille = p.left;
        positionYVille = p.top;
        var scrollY = $(window).scrollTop();
        var scrollX = $(window).scrollLeft();
        classe.css('top', e.clientY + 17 +scrollY).css('left', e.clientX - 90 +scrollX);

        var nomVille = arguments[2];

        $.ajax({
            url:"backend/infoVille.php",
            method:"GET",
            data:{ville:nomVille},
            success:function(data)
            {
                classe.html(data);
            }
        });
}

var positionXCliche = null;
var positionYCliche = null;
var activePopUpCliche = null;

function popUpClicheEnter(e) {
    var classe = $('.popupTextCliche' + arguments[1]);
    if (activePopUpCliche != '.popupTextCliche' + arguments[1]) {
        $(".popupTextCliche").hide();
    }

    classe.toggle();
    activePopUpCliche = '.popupTextCliche' + arguments[1];

    var p = classe.position();
    positionXCliche = p.left;
    positionYCliche = p.top;
    var scrollY = $(window).scrollTop();
    var scrollX = $(window).scrollLeft();
    classe.css('top', e.clientY + 17 +scrollY).css('left', e.clientX - 90 +scrollX);

    var id_cliche = arguments[1];

    $.ajax({
        url:"backend/infoCliche.php",
        method:"GET",
        data:{id_cliche:id_cliche},
        success:function(data)
        {
            classe.html(data);
        }
    });
}


function delay(callback, ms) {
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}




function load_data(query,page, sorted, rowNumbers, perimetre)
{
    $.ajax({
        url:"backend/getDatabase.php",
        method:"GET",
        data:{query:query, page:page, sorted:sorted, rowNumbers:rowNumbers, perimetre:perimetre},
        success:function(data)
        {
            $('#target-content').html(data);

            if($(".modificationsTab").length){
                $("#interface_table thead > tr > th").width("14.285%");
                $("#interface_table tbody > tr > td").width("14.285%");


            } else {
                $("#interface_table thead > tr > th").width("16.666%");
                $("#interface_table tbody > tr > td").width("16.666%");
            }

            modifyArticle();
        }
    });

}


function load_details(id_cliche)
{
    id_cliche  = id_cliche.substring(19, id_cliche.length);
    $.ajax({
        url:"backend/detailsArticle.php",
        method:"GET",
        data:{id_cliche: id_cliche},
        success:function(data)
        {
            $('.'+id_cliche).html(data);
        }
    });
}




function modifyArticle(){
    var articleModify = $('.articleModify');

    $(document).on('click', '.modificationInterface p:first-of-type', function(){
        articleModify.css('display', 'block');
        var id_cliche = $(this).attr('class').substring(6);

        $.ajax({
            url:"backend/popUpModifyArticle.php",
            method:"GET",
            data:{id_cliche: id_cliche},
            success:function(data)
            {
                articleModify.html(data);
            }
        });

    });

    $(window).click(function(e) {
        if(e.target.className == 'articleModify'){
            articleModify.css('display', 'none');
        }
    });

    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            articleModify.css('display', 'none');
        }
    });
}

