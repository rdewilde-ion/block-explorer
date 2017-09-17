$( document ).ready(function() {




        // Initiate the chart
        $('#container2').highcharts('Map', {

            chart: {
            backgroundColor: '#043D51',
        },

        title : {
            text : 'ION Network Map',
            style: {
                color: '#E0E0E3',
                fontFamily: '"Montserrat", sans-serif',
                textTransform: 'uppercase',
                fontSize: '25px',
            }
        },
            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },
            
            colorAxis: {
                min: 1,
                minColor: '#5297af',
                maxColor: '#065571',
            },

            series : [{
                data : cdata,
                mapData: Highcharts.maps['custom/world-highres'],
                joinBy: 'hc-key',
                nullColor: '#cde1e8',
                name: 'Connections',
                states: {
                    hover: {
                        color: '#b4d2dd'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }]
        });



});