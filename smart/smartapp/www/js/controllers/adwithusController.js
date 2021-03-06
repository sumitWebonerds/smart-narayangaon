
app.controller('AddCntrl',function($scope,$http,$state,sessionService,API_BASE,ionicTimePicker,$cordovaGeolocation,$ionicModal,$ionicLoading) {

    $scope.openTimePicker1 = function() {
        var ipObj1 = {
            callback: function(val) {
                if (typeof(val) === 'undefined') {
                    console.log('Time not selected');
                } else {
                    var selectedTime = new Date(val * 1000);
                    $scope.data.from = selectedTime.getUTCHours() + ":" + selectedTime.getUTCMinutes();
                }
            },
            inputTime: 8,
            format: 12,
            setLabel: 'Set'
        };
        if (ionicTimePicker)
            ionicTimePicker.openTimePicker(ipObj1);
    };

    $scope.openTimePicker2 = function() {
        var ipObj1 = {
            callback: function(val) {
                if (typeof(val) === 'undefined') {
                    console.log('Time not selected');
                } else {

                    var selectedTime = new Date(val * 1000);
                    console.log(selectedTime);
                    $scope.data.to = selectedTime.getUTCHours() + ":" + selectedTime.getUTCMinutes();
                }
            },
            inputTime: 8,
            format: 12,
            setLabel: 'Set'
        };
        if (ionicTimePicker)
            ionicTimePicker.openTimePicker(ipObj1);
    };



	$scope.data={};
	  $scope.reset = function() {
        $scope.data ={};
    };
    
      $scope.errorlist =false; 

	  $scope.submitForm = function(frm) {

        if(frm.$valid)
        {
            	$http({
            		method: 'POST',
            		url : API_BASE + 'vendorsapi/create-new',
            		data:$scope.data, 
            		headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            	}).success(function(res) {		              		
		            
                    if(res.status == 'success')
                    {
		             //$scope.message = data[0].message;	
                        alert(res.message);
                     $scope.reset();
                    }
                    else
                    {
                        $scope.errorlist  = res.data; 
                    }
          		});
            } // Validation If condition END
        };


//Map code


document.getElementById("getPosition").addEventListener("click", getPosition);
document.getElementById("watchPosition").addEventListener("click", watchPosition);
function getPosition() {

   var options = {
      enableHighAccuracy: true,
      maximumAge: 3600000
   }
    
   var watchID = navigator.geolocation.getCurrentPosition(onSuccess, onError, options);

   function onSuccess(position) {

      alert('Latitude: '          + position.coords.latitude          + '\n' +
         'Longitude: '         + position.coords.longitude         + '\n' +
         'Altitude: '          + position.coords.altitude          + '\n' +
         'Accuracy: '          + position.coords.accuracy          + '\n' +
         'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
         'Heading: '           + position.coords.heading           + '\n' +
         'Speed: '             + position.coords.speed             + '\n' +
         'Timestamp: '         + position.timestamp                + '\n');
   };

   function onError(error) {
      alert('code: '    + error.code    + '\n' + 'message: ' + error.message + '\n');
   }
}

function watchPosition() {

   var options = {
      maximumAge: 3600000,
      timeout: 3000,
      enableHighAccuracy: true,
   }

   var watchID = navigator.geolocation.watchPosition(onSuccess, onError, options);

   function onSuccess(position) {

      alert('Latitude: '          + position.coords.latitude          + '\n' +
         'Longitude: '         + position.coords.longitude         + '\n' +
         'Altitude: '          + position.coords.altitude          + '\n' +
         'Accuracy: '          + position.coords.accuracy          + '\n' +
         'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
         'Heading: '           + position.coords.heading           + '\n' +
         'Speed: '             + position.coords.speed             + '\n' +
         'Timestamp: '         + position.timestamp                + '\n');
   };

   function onError(error) {
      alert('code: '    + error.code    + '\n' +'message: ' + error.message + '\n');
   }

}
});
