<?php
/**
 * Created by PhpStorm.
 * User: vvm
 * Date: 07.02.18
 * Time: 23:36
 */

namespace App;


class Creator
{
    static function GetConfig($domain, $type=null){
        $path = '/var/www/' . $domain;
        switch ($type){
            case 'laravel':
                $webpath = $path . '/public';
            break;
            default:
                $webpath = $path;
            break;
        }
        if(!file_exists($webpath)){
            die("$webpath does not exist\r\n");
        }
        if(!file_exists($path)){
            die("$path does not exist\r\n");
        }
        return <<<EOT
<VirtualHost *:80>
	# The ServerName directive sets the request scheme, hostname and port that
	# the server uses to identify itself. This is used when creating
	# redirection URLs. In the context of virtual hosts, the ServerName 
	# specifies what hostname must appear in the request's Host: header to
	# match this virtual host. For the default virtual host (this file) this
	# value is not decisive as it is used as a last resort host regardless.
	# However, you must set it for any further virtual host explicitly.
	ServerName $domain

	ServerAdmin webmaster@localhost
	DocumentRoot $webpath

	# Available loglevels: trace8, ..., trace1, debug, info, notice, warn,
	# error, crit, alert, emerg.
	# It is also possible to configure the loglevel for particular
	# modules, e.g.
	#LogLevel info ssl:warn

	ErrorLog $path/error.log
	CustomLog $path/access.log combined
	# For most configuration files from conf-available/, which are
	# enabled or disabled at a global level, it is possible to
	# include a line for only one particular virtual host. For example the
	# following line enables the CGI configuration for this host only
	# after it has been globally disabled with "a2disconf".
	#Include conf-available/serve-cgi-bin.conf
</VirtualHost>

<Directory $webpath>
	AllowOverride All
</Directory>

# vim: syntax=apache ts=4 sw=4 sts=4 sr noet

EOT;

    }
    static function CreateHost($domain){
        $hostfile = file_get_contents('/etc/hosts');
        if(!strpos($hostfile, $domain)){
            $hostfile .= "\r\n127.0.0.1\t" . $domain;
            file_put_contents('/etc/hosts', $hostfile);
        }
    }
}