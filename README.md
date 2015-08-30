
VISUALISING TZSUSD RATE  USING GOOGLE CHARTS
--------------------------------------------
HOW TO USE:
----------
- You can view the TZSUSD rate demo site on this link http://ongeza.wenyeji.com/fronty/
- The end point providing data for the graph is http://ongeza.wenyeji.com/api/rates

APPLICATION STRUCTURE
----------------------
This application is divided into two components:
Server 
-------
- This is the api providing us the the data in json format.
Components are the normally the business layer,the repos and the models.
- The api has been built using yii framework,therefore the developer must be having a working understanding 	of  the yii framework

Client
--------
- This is the client consuming the json api and visualizing the data.
- The location of the client source code is /ongeza_rate/web/fronty.
- It has a method by th game getRate which receives TZSUSD rates data in json format using curl and then 			decodes them.
- The code used is as below.


		function getRate()
		{
		$url = "http://ongeza.wenyeji.com/api/rates";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;    
		}

GET THE API DATA FOR THE PAST ONE MONTH
----------------------------------------

- Download TZSUSD exchange rate for the past 30 days in CSV format on the site below:
https://www.quandl.com/data/CURRFX/USDTZS-Currency-Exchange-Rates-USD-vs-TZS
- Create api controller
- create a rates action method and insert the following code
      
      
        $file_name='CURRFX-USDTZS.csv';
        foreach (file($file_name) as $row) {
            $rower=  explode(',', $row);
            $y=  date('Y',  strtotime($rower[0]));
            $m=  date('m',  strtotime($rower[0]));
            $d=  date('d',  strtotime($rower[0]));
            $date="new Date(".$y.",".$m.",".$d.")";
            $date= trim($date);
            $data[]=array(
                $y,$m,$d,intval($rower[1]),  intval($rower[2]),  intval(str_replace("\n",'',$rower[3]))
            );
            
        }
        //remove the column names in the data
        array_shift($data);
        print_r(json_encode($data));



SERVER SET UP USING  APACHE2
------------------------------
- Create a ongeza.wenyeji.com.conf in /etc/apache2/sites-available.
- The contents should be as follows

		<VirtualHost *:80>
		ServerName ongeza.wenyeji.com
		ServerAlias www.wenyeji.com
		ServerAdmin webmaster@localhost
		DocumentRoot /srv/apps/ongeza_rate/web
	
		ErrorLog ${APACHE_LOG_DIR}/error.log
		CustomLog ${APACHE_LOG_DIR}/access.log combined
	
		# For most configuration files from conf-available/, which are
		# enabled or disabled at a global level, it is possible to
		# include a line for only one particular virtual host. For example the
		# following line enables the CGI configuration for this host only
		# after it has been globally disabled with "a2disconf".
		#Include conf-available/serve-cgi-bin.conf
	        <Directory />
	                Options FollowSymLinks
			AllowOverride All
			Require all granted
	        </Directory>
	        <Directory /srv/apps/ongeza_rate/web/>
	                Options Indexes FollowSymLinks MultiViews
			AllowOverride All
		        Require all granted
	    # use mod_rewrite for pretty URL support
	    #RewriteEngine on
	    # If a directory or a file exists, use the request directly
	    RewriteCond %{REQUEST_FILENAME} !-f
	    RewriteCond %{REQUEST_FILENAME} !-d
	    # Otherwise forward the request to index.php
	    RewriteRule . index.php
	        </Directory>
	
	</VirtualHost>

- enable the site on apache using the following command: sudo  a2ensite ongeza.wenyeji.com.conf 
- Reload apache2 using the command sudo  service apache2 reload
- in the /etc folder,add the following line in the hosts file
127.0.1.1       ongeza.wenyeji.com
-restart apache2 server using the following command:  sudo service apache2 restart

