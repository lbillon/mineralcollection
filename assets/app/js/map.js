angular.module('app', ['ngSanitize', 'ngRoute', 'google-maps'.ns()])

    .config(['GoogleMapApiProvider'.ns(), function (GoogleMapApi) {
        GoogleMapApi.configure({
            key: 'AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc',
            v: '3.17',
            libraries: 'weather,geometry,visualization'
        });
    }])
    
    .controller("map",['$scope', 'GoogleMapApi'.ns(), function ($scope, GoogleMapApi) {

			$scope.map = { center: { latitude: 45, longitude: -73 }, zoom: 8 };

        /*
        * GoogleMapApi is a promise with a
        * then callback of the google.maps object
        *   @pram: maps = google.maps
        */
        GoogleMapApi.then(function(maps) {

        });
    }]);