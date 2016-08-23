<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script>          
            var baseUrl = 'http://localhost/laravel/v1/';
            var accessToken = '';
            
            // Override global ajax config
            $.ajaxSetup({                
                beforeSend: function(xhttp, options)
                {
                    options.url = baseUrl + options.url;
                
                    // Set access token if already available.
                    if (accessToken)
                    {
                        xhttp.setRequestHeader('Authorization', 'Bearer ' + accessToken);
                    }
                }
            });
            
            function getAccessToken()
            {
                $.ajax({                    
                    url: 'token',
                    type: 'POST',
                    data: {
                        email: 'jan.perez28@gmail.com',
                        password: '123'
                    },                    
                    success: function(response)
                    {                
                        accessToken = response.data.token;
                    }
                });               
            }     
            
            function logout()
            {
                $.ajax({   
                    url: 'logout',
                    success: function()
                    {
                        console.log('ok');
                    }
                });
            }
        </script>
	</head>
	<body>        
        <button id="test"></button>   
	</body>
</html>