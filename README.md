
HOW TO USE:
You can view the TZSUSD rate on this link http://ongeza.wenyeji.com/fronty/
The end point providing data for the graph is http://ongeza.wenyeji.com/api/rates
GET THE API DATA FOR THE PAST ONE MONTH
_______________________________________
The api has been built using yii framework,the developer must be having an understanding of how the yii framework works
1.Download TZSUSD exchange rate for the past 30 days in CSV format on the site below:
https://www.quandl.com/data/CURRFX/USDTZS-Currency-Exchange-Rates-USD-vs-TZS
2.create api controller
3.create a rates action method and insert the following code
      
      
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
1.create a ongeza.wenyeji.com.conf in /etc/apache2/sites-available
The contents should be as follows
<VirtualHost *:80>
	# The ServerName directive sets the request scheme, hostname and port that
	# the server uses to identify itself. This is used when creating
	# redirection URLs. In the context of virtual hosts, the ServerName
	# specifies what hostname must appear in the request's Host: header to
	# match this virtual host. For the default virtual host (this file) this
	# value is not decisive as it is used as a last resort host regardless.
	# However, you must set it for any further virtual host explicitly.
	#ServerName www.example.com

	ServerName ongeza.wenyeji.com
	ServerAlias www.wenyeji.com
	ServerAdmin webmaster@localhost
	DocumentRoot /srv/apps/ongeza_rate/web

	# Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	# error, crit, alert, emerg.
	# It is also possible to configure the loglevel for particular
	# modules, e.g.
	#LogLevel info ssl:warn

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


2.enable the site on apache using the following command: sudo  a2ensite ongeza.wenyeji.com.conf 
3.Reload apache2 using the command sudo  service apache2 reload
4. in the /etc folder,add the following line in the hosts file
127.0.1.1       ongeza.wenyeji.com
5.restart apache2 server using the following command:  sudo service apache2 restart

