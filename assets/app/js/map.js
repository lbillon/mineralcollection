var markers = 
    {
        "Ancienne carrière" : "CarriereAncienne.png",
        "Carrière" : "Carriere.png",
        "Ancienne mine" : "MineAncienne.png",
        "Mine" : "Mine.png",
        "Puy de mine" : "puyMine.tif",
        "Puy de mine comblé" : "puyMineComble.bmp",
        "Galerie" : "Galerie.png",
        "Haldes" : "BRGM.png",
        "Plein champ" : "BRGM.png",
        "Rivière" : "BRGM.png",
        "Plage" : "BRGM.png",
        "Travaux" : "BRGM.png",
        "Recherches" : "BRGM.png",
        "Indice" : "Indice.png",
        "Filon" : "Filon.png"
    };

angular.module('app', ['ngSanitize', 'ngRoute', 'ngStorage', 'google-maps'.ns()])

    .config(['GoogleMapApiProvider'.ns(), function (GoogleMapApi) {
        GoogleMapApi.configure({
            key: 'AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc',
            v: '3.17',
            libraries: 'weather,geometry,visualization'
        });
    }])

    .controller("map",['$scope', 'GoogleMapApi'.ns(), '$http', '$localStorage', function ($scope, GoogleMapApi, $http, $localStorage ) {

        $scope._={};

        if (!Date.now) {
            Date.now = function() { return new Date().getTime(); };
        }

        $scope.$storage = $localStorage.$default({
            archive: []
        });


        $scope.map = { center: { latitude: 45.4, longitude: 2.1 }, zoom: 5 };

        $scope.markerClicked = function () {
            console.log(this);

            /*
             var onMarkerClicked = function (marker) {
             marker.showWindow = true;
             $scope.$apply();
             //window.alert("Marker: lat: " + marker.latitude + ", lon: " + marker.longitude + " clicked!!")
             };
             */
        }

        /*
         * GoogleMapApi is a promise with a
         * then callback of the google.maps object
         *   @pram: maps = google.maps
         */
        GoogleMapApi.then(function(maps) {
            // map loaded

            var height = window.innerHeight - 52;
            $('.angular-google-map-container').css( "height", height+"px" );


        });


        $(window).resize(function(){
            //alert(window.innerWidth);

            $scope.$apply(function(){
                var height = window.innerHeight - 52;
                $('.angular-google-map-container').css( "height", height+"px" );
            });
        });


        $scope.mouseEnter = function () {

            $( ".search-map-overlay" ).animate({
                opacity: 1
            }, 100, function() {

            });

            $(".data-list-wrapper").show();
            $( ".data-list-wrapper" ).animate({
                height: "185px",
                opacity: 1
            }, 200, function() {

            });
        }

        $scope.mouseLeave = function () {

            $( ".search-map-overlay" ).animate({
                opacity: 0.75
            }, 100, function() {

            });

            $( ".data-list-wrapper" ).animate({
                height: "0px",
                opacity: 0
            }, 200, function() {
                $(".data-list-wrapper").hide();
            });
        }



// _.archive_btn

        $scope.$watch('_.archive_btn', function (val){
            if (val) {
                $(".s-right").hide();
                $(".s-right").css( "opacity", 0 );
                $( ".s-left" ).animate({
                    width: "370px"
                }, 200, function() {

                });
                delete $scope._.archive_selected;
            } else {
                $( ".s-left" ).animate({
                    width: "50%"
                }, 200, function() {
                    $( ".s-right" ).show();
                    $( ".s-right" ).animate({
                        opacity: "1"
                    }, 150, function() {

                    });

                });


            }
        });

        $scope._.archive_btn=true;

        $scope.loadQuery = function () {

            angular.forEach($scope.$storage.archive, function (value, key) {
                if (value.id === $scope._.archive_selected) {
                    $scope.query = value.query;
                }
            });


        }

        $scope.delete = function (id) {

            var i = null;m
            angular.forEach($scope.$storage.archive, function (value, index) {

                if (value.id === id) {
                    i = index;
                }

            });

            if (i!==null) {
                $scope.$storage.archive.splice(i, 1);
            }

        }

        $scope.save = function () {
            var entry = {
                id: Date.now(),
                name: $scope._.save_name,
                query: $scope.query
            };

            var ok = true;

            angular.forEach($scope.$storage.archive, function (value, key) {
                if (value.name === entry.name) {
                    ok = false;
                    alert("Entry with name '"+entry.name+"' already exists!");
                } else if (value.query === entry.query) {
                    ok = false;
                    alert("Entry with your query already exists!");
                }
            });

            if (ok) {
                $scope.$storage.archive.push(entry);
                $scope._.save_name = null;
            }

        }

        function muteAnimations() {
            angular.forEach($scope.data, function (value, index) {
                if (value.options.animation === 1) {
                    value.options.animation = 0;
                    value.options.labelContent = "";
                }
            });
        }

        $scope.zoomto = function (el) {
            if (el.longitude && el.latitude) {
                $scope.map = { center: { latitude: el.latitude, longitude: el.longitude }, zoom: 10 };

                muteAnimations();
                el.options.animation = 1;
                el.options.labelContent = el.name;
            }
        }

        $scope.query = "SELECT s.SiteId as id, s.SiteNom name, s.SiteDescrGen as description, s.SiteType as type, c.longitude as longitude, c.latitude as latitude FROM Sites s, Communes c WHERE s.CommuneId=c.CommuneId";


        function modify(data) {

            angular.forEach(data, function (value, index) {
                value.onClick = function (marker) {
                    console.log(marker);
                    marker.showWindow = true;
                    $scope.$apply();
                };
                var iconPath = "../../assets/app/img/";
                var currentType = value.type;

                if (typeof markers[currentType] != 'undefined')
                    iconPath+=markers[currentType];
                else
                    iconPath+='default.png'
                value.options = {
                    animation: 0,
                    labelAnchor: "22 0",
                    labelContent: '',
                    icon : iconPath
                    //labelClass: "marker-labels"
                }
            });

            return data;

        }


        $scope.execute = function () {

            $scope.data = [];

            var post = $.param({query:angular.copy($scope.query)});

            $http.post('../map_endpoints/querySearch', post, {
                headers : {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }).
                success(function(data, status, headers, config) {
                    if (!data.error) {
                        var modified = modify(data.result);
                        $scope.data = data.result;
                    } else {
                        alert(data.msg);
                    }

                }).
                error(function(data, status, headers, config) {

                });


        }
    }]).controller("picker",['$scope', 'GoogleMapApi'.ns(), '$http', '$localStorage', function ($scope, GoogleMapApi, $http, $localStorage ) {





        center_changed=function(e){
            $('#field-Latitude').val(e.center.lat());
            $('#field-Longitude').val(e.center.lng());

        }

        var center={ latitude: 47, longitude: 3 };

        if( $('#field-Latitude').val() ||  $('#field-Longitude').val()){
            center={ latitude: $('#field-Latitude').val(), longitude: $('#field-Longitude').val() };

        }


        $scope._={};
        $scope.map = { center: center, zoom: 5 ,events:{'center_changed':center_changed}};
        $scope.marker = {
            id: 0,
            coords: {
                latitude: 0,
                longitude: 0
            }

        };







        /*
         * GoogleMapApi is a promise with a
         * then callback of the google.maps object
         *   @pram: maps = google.maps
         */
        GoogleMapApi.then(function(maps) {
            // map loaded

            var height = 500;
            $('.angular-google-map-container').css( "height", height+"px" );


        });








    }]);