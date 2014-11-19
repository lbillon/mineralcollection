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
        
			$scope.query = "SELECT s.SiteId as id, s.SiteNom name, s.SiteDescrGen as description, c.longitude as longitude, c.latitude as latitude FROM Sites s, Communes c WHERE s.CommuneId=c.CommuneId";        
        
        $scope.execute = function () {
        	
        			var post = $.param({query:angular.copy($scope.query)});
		
					$http.post('/mineralcollection/index.php/map_endpoints/querySearch', post, {
							headers : {
								'Content-Type': 'application/x-www-form-urlencoded'
							}
						}).
  						success(function(data, status, headers, config) {
							if (!data.error) {
								$scope.data = data.result;
							} else {
								alert(data.msg);							
							}
    						
    					}).
    					error(function(data, status, headers, config) {
							
  						});
               
						
			}
    }]);