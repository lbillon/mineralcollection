angular.module('app', ['ngSanitize', 'ngRoute', 'google-maps'.ns()])

    .config(['GoogleMapApiProvider'.ns(), function (GoogleMapApi) {
        GoogleMapApi.configure({
            key: 'AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc',
            v: '3.17',
            libraries: 'weather,geometry,visualization'
        });
    }])
    
    .controller("map",['$scope', 'GoogleMapApi'.ns(), '$http', function ($scope, GoogleMapApi, $http) {

			$scope.map = { center: { latitude: 45, longitude: -73 }, zoom: 8 };

        /*
        * GoogleMapApi is a promise with a
        * then callback of the google.maps object
        *   @pram: maps = google.maps
        */
        GoogleMapApi.then(function(maps) {
				// map loaded				
				
				
        });
        
			$scope.query = "SELECT * FROM sites WHERE 1;";        
        
        $scope.execute = function () {
		
					$http.post('/mineralcollection/index.php/map_endpoints/querySearch', {query:angular.copy($scope.query)}).
  						success(function(data, status, headers, config) {
    						console.log(data);
    					}).
    					error(function(data, status, headers, config) {
							
  						});
						
			}
    }]);