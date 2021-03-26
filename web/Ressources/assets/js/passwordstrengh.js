$(document).ready(function () {
    //disable the submit button
    $("#submit_button").attr("disabled", true);
    return true;    

    });

    var myApp = angular.module("myapp", []);
    myApp.controller("PasswordController", function($scope) {

        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");
        var mediumRegex = new RegExp("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})");

        $scope.passwordStrength = {
            
        };

        $scope.analyze = function(value) {
            if(strongRegex.test(value)) 
            {
                $scope.passwordStrength["border-color"] = "green";
                $(document).ready(function () {
                    //disable the submit button
                    $("#submit_button").attr("disabled", false);
                    return true;    
            
                    });
                
            } else if(mediumRegex.test(value)) {
                $scope.passwordStrength["border-color"] = "orange";
                $(document).ready(function () {
                    //disable the submit button
                    $("#submit_button").attr("disabled", true);
                    return true;    
            
                    });
            } else {
                $scope.passwordStrength["border-color"] = "red";
                $(document).ready(function () {
                    //disable the submit button
                    $("#submit_button").attr("disabled", true);
                    return true;    
            
                    });
            }
        };

    });