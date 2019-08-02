$(document).ready(function() {

    $('.statistiques_container').load('../../backend/requetes.php');

    top25Villes();
    top5Formats();
    top25Moyen();

});


function top25Villes(){

    $.ajax({
        url : "../../backend/dataVilles.php",
        type : "GET",
        success : function(data){
            data = JSON.parse(data);

            var nb_article = [];
            var nom = [];
            var enregistrement = [];


            for (var i in data) {
                nb_article.push(data[i][0]);
                nom.push(data[i][1].charAt(0).toUpperCase() + data[i][1].slice(1));
                enregistrement.push(data[i][2]);
            }

            var chartdata = {
                labels: nom,
                datasets: [
                    {
                        label: 'Clichés par ville',
                        backgroundColor: '#5272d3',
                        borderColor: '#354a89',
                        hoverBackgroundColor: '#2e5ff2',
                        hoverBorderColor: '#2857e2',
                        height: '100px',
                        data: enregistrement
                    },
                    {
                        label: 'Articles par ville',
                        backgroundColor: '#449e35',
                        borderColor: '#368229',
                        hoverBackgroundColor: '#3ecc26',
                        hoverBorderColor: '#2ea01b',
                        data: nb_article
                    }
                ]
            };

            var graphTarget = $("#topVilles");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    scaleShowValues: true,
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false
                            }
                        }]
                    },
                    legend: {
                        onClick: null
                    }
                },
            });

        },
        error : function(data) {
            console.log(data);
        }
    });
}




function top5Formats(){

    $.ajax({
        url : "../../backend/dataFormats.php",
        type : "GET",
        success : function(data){
            data = JSON.parse(data);

            var nb_cliches = [];
            var tailles = [];

            for (var i in data) {
                nb_cliches.push(data[i][2]);
                tailles.push(data[i][0] + "x" + data[i][1]);
            }

            var chartdata = {
                labels: tailles,
                datasets: [
                    {
                        label: 'Clichés par format',
                        backgroundColor: '#279191',
                        borderColor: '#1e6d6d',
                        hoverBackgroundColor: '#2bdbdb',
                        hoverBorderColor: '#28cccc',
                        data: nb_cliches
                    }
                ]
            };

            var graphTarget = $("#topFormats");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    scaleShowValues: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false
                            }
                        }]
                    },
                    legend: {
                        onClick: null
                    }
                },
            });

        },
        error : function(data) {
            console.log(data);
        }
    });


}




function top25Moyen() {

    $.ajax({
        url: "../../backend/dataMoyen.php",
        type: "GET",
        success: function (data) {
            data = JSON.parse(data);

            var nb_cliches_moyen = [];
            var nom_ville = [];

            for (var i in data) {
                nom_ville.push(data[i][0].charAt(0).toUpperCase() + data[i][0].slice(1));
                nb_cliches_moyen.push(parseFloat(data[i][1]).toFixed(2));
            }

            var chartdata = {
                labels: nom_ville,
                datasets: [
                    {
                        label: 'Nombre de clichés moyen par article dans une ville',
                        backgroundColor: '#8c2b2b',
                        borderColor: '#b22e2e',
                        hoverBackgroundColor: '#d61313',
                        hoverBorderColor: '#c61919',
                        data: nb_cliches_moyen
                    }
                ]
            };

            var graphTarget = $("#topMoyen");

            var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata,
                options: {
                    maintainAspectRatio: false,
                    scaleShowValues: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                autoSkip: false
                            }
                        }]
                    },
                    legend: {
                        onClick: null
                    }
                },
            });

        },
        error: function (data) {
            console.log(data);
        }
    });
}
