<?php
/**
 * WssSoapClient
 *
 * WS-Security implementation for web service EFacture (postfinance) 
 */

class WssSoapClient extends SoapClient
{
    /** @var string  */
    protected $loaction = null;

    /** @var string */
    protected $username = null;

    /** @var string */
    protected $password = null;

    /** @var string  */
    protected $ns1_schema = null;

    /**
     * @param mixed $wsdl
     * @param array $options
     */
    public function __construct($wsdl, array $options = null, $username, $password, $ns1_schema = 'http://www.yellowworld.ch')
    {
        preg_match("/(.*)\?.*/", $wsdl, $location);

        $this->location = $location[1];
        $this->username = $username;
        $this->password = $password;
        $this->ns1_schema = $ns1_schema;

        parent::__construct($wsdl, $options);
    }

    /**
     * Extend doRequest to encapsulate wss protocole
     *
     * @param string $request
     * @param string $location
     * @param string $saction
     * @param int $version
     *
     * @return string
     */
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $currentTime = time();
        $created = gmdate("Y-m-d\TH:i:s", $currentTime).'Z';
        $expire  = gmdate("Y-m-d\TH:i:s", $currentTime + 3600).'Z';

        $nonce = md5(uniqid(rand(), true));
        $guid = 'udi:'.
            substr($nonce,0,8)."-".
            substr($nonce,8,4)."-".
            substr($nonce,12,4)."-".
            substr($nonce,16,4)."-".
            substr($nonce,20,12);

        preg_match("/<SOAP\-ENV:Body>(.*)<\/SOAP\-ENV:Body>/", $request, $body);
        preg_match("/<ns1:([a-zA-Z]+)><ns1:.*/", $request, $method);

        $request =
            '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope
              xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/"
              xmlns:ns1="'.$this->ns1_schema.'"
              xmlns:wsa="http://schemas.xmlsoap.org/ws/2004/08/addressing"
            >
            <SOAP-ENV:Header>
                <wsa:Action>http://www.yellowworld.ch/'.$method[1].'</wsa:Action>
	<wsa:To>'.$this->location.'</wsa:To>
	<wsa:MessageID>'.$guid.'</wsa:MessageID>
	<wsa:ReplyTo>
		<wsa:Address>http://schemas.xmlsoap.org/ws/2004/08/addressing/role/anonymous</wsa:Address>
	</wsa:ReplyTo>
	<wsse:Security
	 xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"
	 SOAP-ENV:mustUnderstand="1"
	>
		<wsse:UsernameToken>
			<wsse:Username>'.$this->username.'</wsse:Username>
			<wsse:Password
			 Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText"
			>'.$this->password.'</wsse:Password>
			<wsse:Nonce>'.base64_encode($nonce).'</wsse:Nonce>
			<wsu:Created
			 xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd"
			>'.$created.'</wsu:Created>
		</wsse:UsernameToken>
		<wsu:Timestamp
		 xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
			<wsu:Created>'.$created.'</wsu:Created>
			<wsu:Expires>'.$expire.'</wsu:Expires>
		</wsu:Timestamp>
	</wsse:Security>
</SOAP-ENV:Header>
<SOAP-ENV:Body>
	'.$body[1].'
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>';

        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }

}
