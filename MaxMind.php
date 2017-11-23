<?php


class MaxMind
{
    const USERID = '';   // userd_id maxmind
    const LICENSEKEY = '';  // license_key maxmind
 
    public function getLocation($user_ip)
    {

        //Authorization for the resource
        $authenticationKey = sprintf('%s:%s', self::USERID, self::LICENSEKEY);
        $authenticationKey = base64_encode($authenticationKey);
        $httpOptions = [
            'http' => [
                'method' => 'GET',
                'header' => 'Authorization: Basic ' . $authenticationKey . "\r\n"
            ]
        ];
 
        $context = stream_context_create($httpOptions);
        
        //json answer
        $geo = file_get_contents("https://geoip.maxmind.com/geoip/v2.1/insights/$user_ip", false, $context);
        $geo = json_decode($geo, true);
        $user_location = [];
 
        //city
        $user_location['city_confidence'] = $geo["city"]["confidence"];
        $user_location['city_id'] = $geo["city"]["geoname_id"];
        $user_location['city_name'] = $geo["city"]["names"]["en"];
 
        //continent
        $user_location['continent_code'] = $geo["continent"]["code"];
        $user_location['continent_id'] = $geo["continent"]["geoname_id"];
        $user_location['continent_name'] = $geo["continent"]["names"]["en"];
 
        //country
        $user_location['country_confidence'] = $geo["country"]["confidence"];
        $user_location['country_iso_code'] = $geo["country"]["iso_code"];
        $user_location['country_id'] = $geo["country"]["geoname_id"];
        $user_location['country_name'] = $geo["continent"]["names"]["en"];
 
        //location
        $user_location['location_radius'] = $geo["location"]["accuracy_radius"];
        $user_location['latitude'] = $geo["location"]["latitude"];
        $user_location['longitude'] = $geo["location"]["longitude"];
        $user_location['time_zone'] = $geo["location"]["time_zone"];
 
        //maxmind
        $user_location['maxmind'] = $geo["maxmind"]["queries_remaining"];
 
        //subdivision
        $user_location['location_conf'] = $geo["subdivisions"][0]["confidence"];
        $user_location['location_iso_code'] = $geo["subdivisions"][0]["iso_code"];
        $user_location['location_id'] = $geo["subdivisions"][0]["geoname_id"];
        $user_location['location_name'] = $geo["subdivisions"][0]["names"]["en"];
 
        //traits
        $user_location['user_type'] = $geo["traits"]["user_type"];
        $user_location['autonomous_system_number'] = $geo["traits"]["autonomous_system_number"];
        $user_location['autonomous_system_organization'] = $geo["traits"]["autonomous_system_organization"];
        $user_location['autonomous_domain'] = $geo["traits"]["domain"];
        $user_location['autonomous_isp'] = $geo["traits"]["isp"];
        $user_location['autonomous_organiztion'] = $geo["traits"]["organization"];

        $user_location['ip_addres'] = $geo["traits"]["ip_address"];

 
        return $user_location;
    }
}
 
 