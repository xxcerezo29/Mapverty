@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Map</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">
                        Map
                    </li>
                </ol>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="course">Layers</label>
                            <select name="layer"
                                    class="custom-select form-control-border  @error('layer') is-invalid @enderror"
                                    id="layer">
                                <option value=" "></option>
                                <option value="municipality" selected>City/Municipality</option>
                                <option value="barangay" >Barangay</option>
                            </select>
                        </div>
                        <div id="forLayerBarangay" class="col-md-4">
                            <label for="course">Municipalities</label>
                            <select name="municity"
                                    class="custom-select form-control-border  @error('municity') is-invalid @enderror"
                                    id="municity">
                                @foreach($municipalities as $municipality)
                                    <option value="{{$municipality->adm3_pcode}}">{{$municipality->adm3_en}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-5">


                    </div>
                    <p class="text-center" id="map-title">
                    </p>
                    <div id="map"></div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            height: 70vh;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }

        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .legend {
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
        .leaflet-label {
            padding: 8px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            transition: all 0.3s ease-in-out;
        }

        .leaflet-label:hover {
            transform: scale(1.1);
        }

        @media print {
            .leaflet-label {
                display: none;
            }

            #map {
                height: 70vh;
            }

            .info {
                padding: 6px 8px;
                font: 14px/16px Arial, Helvetica, sans-serif;
                background: white;
                background: rgba(255, 255, 255, 0.8);
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                border-radius: 5px;
            }

            .info h4 {
                margin: 0 0 5px;
                color: #777;
            }

            .legend {
                line-height: 18px;
                color: #555;
            }

            .legend i {
                width: 18px;
                height: 18px;
                float: left;
                margin-right: 8px;
                opacity: 0.7;
            }
        }
    </style>
@stop
@section('js')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="/vendor/leaflet.browser.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.js"></script>
    <script src='//api.tiles.mapbox.com/mapbox.js/plugins/leaflet-image/v0.0.4/leaflet-image.js'></script>
    <script src='/vendor/bundle.js'></script>

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

    <script>
        class ChoroplethMap{
            constructor(mapId) {
                this.map = L.map(mapId,{minZoom: 6, maxZoom: 12}).setView([13, 122], 7);
                this.geojson = null;
                this.info = L.control();
                this.labels = [];
                this.densityValues = null;

                this.setupMap();
            }

            setupMap(){

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(this.map);

                this.getColor = null;

                this.createInfoControl();
                this.loadInitialGeoJSON();
                this.EP();



            }
            loadInitialGeoJSON(){
                this.loadGeoJSON().then(({minValue, maxValue, dataRetireve, class_interval}) => {

                    this.CreateGEOJSON(dataRetireve);
                    this.createLegendControler(this, minValue, maxValue, class_interval);

                    this.map.on('zoomend', function () {
                        // Get the current zoom level
                        var zoomLevel = this.map.getZoom();

                        // Check the zoom level and hide/show labels accordingly
                        for (var i = 0; i < this.labels.length; i++) {
                            var label = this.labels[i];
                            if (zoomLevel < 7) {
                                label.setOpacity(0); // hide label
                            } else {
                                label.setOpacity(1); // show label
                            }
                        }
                    }.bind(this));

                    this.map.on('browser-print-start', function (e){
                        L.legendControl({position: 'bottomright'}).addTo(e.printMap);
                    })
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ooop, Unfortunately!',
                        text: error,
                        confirmButtonText: "Ok",

                    });
                })
            }
            EP(){
                this.eP = L.easyPrint({
                    sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
                    filename: 'MaPVerty',
                    exportOnly: true,
                    hideControlContainer: false,
                    drawButtons: function (){
                        var buttons = L.easyPrint.prototype.drawButtons.call(this);

                        // Find and hide the specific buttons (zoom and download)
                        var zoomButton = buttons.querySelector('.leaflet-control-zoom');
                        var downloadButton = buttons.querySelector('.easyPrintBtn');

                        if (zoomButton) {
                            zoomButton.remove();
                        }

                        if (downloadButton) {
                            downloadButton.remove();
                        }

                        return buttons;
                    }
                }).addTo(this.map);
            }

            loadGeoJSON(){
                return new Promise((resolve, reject) => {
                    $('#mapOverlay').show();
                    $('#map-title').empty();
                    $('#map').hide();
                    $.ajax({
                        url: "{{route('spatial.index')}}",
                        type: 'GET',
                        data: {
                            layer: $('#layer').val(),
                            municipality: $('#municity').val()?? null,
                            Selector: '1'
                        },
                        dataType: 'json',
                        success: (dataRetireve) => {
                            this.data = JSON.parse(dataRetireve.data);

                            this.densityValues = this.data.features.map(feature => feature.properties.density);

                            const minValue = _.min(this.densityValues);
                            const maxValue = _.max(this.densityValues);

                            const interval_width = (maxValue - minValue) / 5;
                            const class_interval = [minValue, (minValue + interval_width).toFixed(0), (minValue + (interval_width * 2)).toFixed(0), (minValue + (interval_width * 3)).toFixed(0), (minValue + (interval_width * 4)).toFixed(0)]

                            console.log(class_interval)

                            this.getColor = (d) => {
                                return d > class_interval[4] ? '#800026' :
                                    d > class_interval[3] ? '#BD0026' :
                                        d > class_interval[2] ? '#FC4E2A' :
                                            d > class_interval[1] ? '#FED976' :
                                                '#FFEDA0';
                            }

                            this.CreateGEOJSON(dataRetireve);
                            resolve({ minValue, maxValue, dataRetireve, class_interval });
                        },
                        error: (data) => {
                            $('#mapOverlay').show();
                            $('#map').show();

                            const errorMessage = data.responseJSON.message || 'An error occurred while fetching data.';
                            reject(errorMessage);
                        }
                    });
                })

            }

            CreateGEOJSON(dataRetireve){
                if (this.geojson) {
                    this.map.removeLayer(this.geojson); // Remove the existing layer
                    this.onClear();
                }
                this.geojson = L.geoJson(this.data, {
                    style: this.style.bind(this),
                    onEachFeature: this.EachFeature.bind(this)

                }).addTo(this.map);

                this.map.fitBounds(this.geojson.getBounds());

                $('#map-title').html('<strong>'+dataRetireve.title+'</strong>');
                $('#mapOverlay').hide();
                $('#map').show();

            }

            style(feature) {
                return {
                    fillColor: this.getColor(feature.properties.density),
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7
                };
            }
            // getColor(d, class_interval) {
            //     console.log(this.class_interval);
            //     return d > 100 ? '#800026' :
            //         d > 75 ? '#BD0026' :
            //             d > 50 ? '#FC4E2A' :
            //                 d > 25 ? '#FED976' :
            //                     '#FFEDA0';
            // }

            EachFeature(feature, layer) {
                if(feature.properties.density != 0)
                {
                    // var label = L.marker(layer.getBounds().getCenter(),{
                    //     icon: L.divIcon({
                    //         className: 'leaflet-label',
                    //         html: feature.properties.density
                    //     })
                    // }).addTo(this.map);

                    // layer.label = label;
                    // this.labels.push(label);
                }
                layer.on({
                    mouseover: this.highlightFeature.bind(this),
                    mouseout: this.resetHighlight.bind(this),
                    click: this.onClick.bind(this)
                });
            }

            onClick(e){
                this.map.fitBounds(e.target.getBounds());
            }

            highlightFeature(e) {
                var layer = e.target;

                layer.setStyle({
                    weight: 5,
                    color: '#666',
                    dashArray: '3',
                    fillOpacity: 0.7
                });

                if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                    layer.bringToFront();
                }

                this.info.update(layer.feature.properties);
            }

            resetHighlight(e) {
                var layer = e.target;

                layer.setStyle({
                    weight: 2,
                    opacity: 1,
                    color: 'white',
                    dashArray: '3',
                    fillOpacity: 0.7
                });

                this.info.update();
            }

            createInfoControl(){
                this.info.onAdd = function (map){
                    this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
                    this.update();
                    return this._div;
                }

                this.info.update = function (props){
                    this._div.innerHTML = '<h4>Isabela State University</h4>' + (props ?
                        '<b>' + (props.adm4_en? props.adm4_en : props.adm3_en) + '</b><br />' + props.density + ' people / mi<sup>2</sup>' :
                        'Hover over a state');
                }

                this.info.addTo(this.map);
            }

            createLegendControler(refThis, minValue, maxValue, class_interval){
                if(this.legendControl){
                    this.map.removeControl(this.legendControl);
                }


                this.legendControl = L.control({
                    position: 'bottomright'
                })

                this.legendControl.onAdd = function (map){
                    var div = L.DomUtil.create('div', 'info legend');

                    div.innerHTML = '';

                    if(minValue === 0 && maxValue === 0)
                    {
                        div.innerHTML += '<p class="text-center">No Data Available</p><br>';
                        return div;

                    }else{
                        var level = ['None or Very Low', 'Low', 'Moderate', 'High', 'Very High'];

                        console.log(class_interval)

                        div.innerHTML += '<p class="text-center">Student Below Poverty Line Count </p><br>';
                        for (var i = 0; i < class_interval.length; i++) {
                            div.innerHTML +=
                                '<i style="background:' + refThis.getColor(class_interval[i] + 1) + '"></i> ' +
                                class_interval[i] + (class_interval[i + 1] ? '&ndash;' + class_interval[i + 1] + '     ' +  level[i] + '<br>' : '+' + '     ' +  level[i] + '<br>');
                        }
                    }


                    return div;
                }

                // L.legendControl = function (options) {
                //     return new L.LegendControl(options);
                // }
                this.legendControl.addTo(this.map);
            }

            onClear(){
                for(var i = 0; i < this.labels.length; i++) {
                    this.map.removeLayer(this.labels[i]);
                }

                this.labels = [];
            }

        }

        $('#forLayerBarangay').hide();

        $('#layer').on('change', function (){
            if($('#layer').val() == 'barangay')
            {
                $('#forLayerBarangay').show();
            }
            else
            {
                $('#forLayerBarangay').hide();
                choromap.loadInitialGeoJSON();
            }
        })
        const  choromap = new ChoroplethMap('map');

        $('#municity').on('change', function (){
            choromap.loadInitialGeoJSON();
        })
    </script>
@stop
@section('plugins.Sweetalert2', true)
