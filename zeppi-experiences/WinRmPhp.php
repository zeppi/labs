<?php

class WinRmPhp
{
    const WINRS_URI = "http://schemas.microsoft.com/wbem/wsman/1/windows/shell";
    const WINRS_CMD_URI = "http://schemas.microsoft.com/wbem/wsman/1/windows/shell/cmd";
    const WINRS_CREATE_URI = "http://schemas.xmlsoap.org/ws/2004/09/transfer/Create";
    const WINRS_COMMAND_URI = "http://schemas.microsoft.com/wbem/wsman/1/windows/shell/Command";
    const WINRS_RECEIVE_URI = "http://schemas.microsoft.com/wbem/wsman/1/windows/shell/Receive";
    const WINRS_DELETE_URI = "http://schemas.xmlsoap.org/ws/2004/09/transfer/Delete";

    const SOAP_ENVELOPE_XML = "
        <s:Envelope xmlns:s='http://www.w3.org/2003/05/soap-envelope' 
                    xmlns:a='http://schemas.xmlsoap.org/ws/2004/08/addressing' 
                    xmlns:w='http://schemas.dmtf.org/wbem/wsman/1/wsman.xsd'> 
            <s:Header> 
                <a:To>{Url}</a:To> 
                <w:ResourceURI s:mustUnderstand='true'>{ResourceUri}</w:ResourceURI> 
                <a:ReplyTo> 
                  <a:Address s:mustUnderstand='true'>http://schemas.xmlsoap.org/ws/2004/08/addressing/role/anonymous</a:Address> 
                </a:ReplyTo> 
                <a:Action s:mustUnderstand='true'>{ActionUri}</a:Action> 
                    <w:MaxEnvelopeSize s:mustUnderstand='true'>153600</w:MaxEnvelopeSize> 
                    <a:MessageID>uuid:{MessageId}</a:MessageID>
                    <w:Locale xml:lang='en-US' s:mustUnderstand='false'></w:Locale> 
	                <!--SelectorSet-->
                    <!--OptionSet-->
                    <w:OperationTimeout>PT60.000S</w:OperationTimeout> 
                </s:Header> 
                <s:Body><!--Body--></s:Body> 
            </s:Envelope>";

    const WINRS_CREATE_BODY_XML = "
        <Shell xmlns='http://schemas.microsoft.com/wbem/wsman/1/windows/shell'>
            <InputStreams>stdin</InputStreams>
            <OutputStreams>stdout stderr</OutputStreams>
        </Shell>";

    const WINRS_COMMAND_SELECTOR_XML = "
        <w:SelectorSet>
            <w:Selector Name='ShellId'>{shellid}</w:Selector>
        </w:SelectorSet>";
        
    const WINRS_COMMAND_OPTION_XML = "
        <w:OptionSet><w:Option Name='WINRS_CONSOLEMODE_STDIN'>TRUE</w:Option></w:OptionSet>";

    const WINRS_COMMAND_BODY_XML = "
        <CommandLine xmlns='http://schemas.microsoft.com/wbem/wsman/1/windows/shell'>
            <Command>{Command}</Command>
            <Arguments>{Arguments}</Arguments>
        </CommandLine>";

    const WINRS_RECEIVE_BODY_XML = "
        <Receive SequenceId='{SequenceId}' xmlns='http://schemas.microsoft.com/wbem/wsman/1/windows/shell'>
            <DesiredStream CommandId='{CommandId}'>stdout stderr</DesiredStream>
        </Receive>";

    /**
     * @param string $cmd
     * @param string $args
     */
    public function run($cmd, $args = null)
    {
        $this
            ->create()
            ->command($cmd, $args)
            ->response()
            ->close();
    }
    
    /** @var string */
    protected $shellId = null;

    /**
     * @return $this
     * @throws Exception
     */
    public function create()
    {        
        $create = self::strmap(self::SOAP_ENVELOPE_XML, array(
            'Url' => 'http://localhost:5985/wsman',
            'MessageId' => self::guid(),
            'ResourceUri' => self::WINRS_CMD_URI,
            'ActionUri' => self::WINRS_CREATE_URI,
            'Body' => self::WINRS_CREATE_BODY_XML));

        $send = self::send($create);

        if(!array_key_exists('Shell', $send))
        {
            throw new Exception('Create process failed, Shell not found');
        }
        
        $this->shellId = $send['Shell']['ShellId'];
        return $this;
    }

    /** @var string */
    protected $commandId = null;

    /**
     * @param string $cmd
     * @param string $args
     * 
     * @return $this
     * @throws Exception
     */
    public function command($cmd, $args = null)
    {
        // Retrieve ShellId
        if(is_null($this->shellId)) $this->create();
        
        $command = self::strmap(self::SOAP_ENVELOPE_XML, array(
            'Url' => 'http://localhost:5985/wsman',
            'MessageId' => self::guid(),
            'ResourceUri' => self::WINRS_CMD_URI,
            'ActionUri' => self::WINRS_COMMAND_URI,
            'SelectorSet' => self::strmap(
                self::WINRS_COMMAND_SELECTOR_XML, array('shellid' => $this->shellId)),
            'OptionSet' => self::WINRS_COMMAND_OPTION_XML,
            'Body' => self::strmap(self::WINRS_COMMAND_BODY_XML, array(
                'Command' => $cmd,
                'Arguments' => $args))));
            
            
                
        $send = self::send($command);

        if(!array_key_exists('CommandResponse', $send))
        {
            throw new Exception('Command process failed');
        }
        
        $this->commandId = $send['CommandResponse']['CommandId'];
        return $this;
        
    }
    
    /** @var array  */
    protected $out = array();

    /**
     * @return $this
     */
    public function response()
    {
        $response = self::strmap(self::SOAP_ENVELOPE_XML, array(
            'Url' => 'http://localhost:5985/wsman',
            'MessageId' => self::guid(),
            'ResourceUri' => self::WINRS_CMD_URI,
            'ActionUri' => self::WINRS_RECEIVE_URI,
            'SelectorSet' => self::strmap(
                self::WINRS_COMMAND_SELECTOR_XML, array('shellid' => $this->shellId)),            
            'Body' => self::strmap(self::WINRS_RECEIVE_BODY_XML, array(
                'SequenceId' => $this->shellId,
                'CommandId' => $this->commandId
            ))));

        $send = self::send($response);
        
        foreach($send['ReceiveResponse']['Stream'] as $out)
        {
            if(!is_array($out))
            {
                $this->out[] = base64_decode($out);
            }
        }
        return $this;
    }

    /**
     * 
     * @return $this
     */
    public function close()
    {
        $close = self::strmap(self::SOAP_ENVELOPE_XML, array(
            'Url' => 'http://localhost:5985/wsman',
            'MessageId' => self::guid(),
            'ResourceUri' => self::WINRS_CMD_URI,
            'ActionUri' => self::WINRS_DELETE_URI,
            'SelectorSet' => self::strmap(
                self::WINRS_COMMAND_SELECTOR_XML, 
                array('shellid' => $this->shellId))));

        self::send($close);
        return $this;    
    }

    /**
     * @param string $soap_xml
     * 
     * @return mixed
     * @throws Exception
     */
    protected function send($soap_xml)
    {
        $ch = curl_init('http://<THE IP>:5985/wsman');
        curl_setopt($ch, CURLOPT_USERPWD, "<USERNAME>:<PASSWORD>");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/soap+xml;charset=UTF-8'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $soap_xml);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        $out = curl_exec($ch);
        curl_close($ch);

        if(empty($out)) throw new Exception('Curl output is empty');
        
        if(preg_match('/DeleteResponse/', $out)) return array();
        $xml = self::winRmXmlToArray($out);
        
        return $xml;
    }

    /**
     * @param string $string
     * 
     * @return mixed
     * @throws Exception
     */
    protected static function winRmXmlToArray($string)
    {
        $xml = simplexml_load_string($string);
        $ns = $xml->getNamespaces(true);

        if(!isset($ns['rsp']) || preg_match('/<f:Message>(.*)<\/f:Message>/', $string, $match))
        {
            if(isset($match[1]))
            {
                throw new Exception(strip_tags($match[1]));
            }
            
            throw new Exception('Globale parting error, see out :'.$string);
        }
        
        return $xml = json_decode(json_encode($xml->children($ns['s'])->Body->children($ns['rsp'])), true);    
    }

    /**
     * Simple template 
     * 
     * @param string $string
     * @param string $replacements
     * 
     * @return string
     */
    public static function strmap($string, $replacements = null)
    {
        if(empty($replacements)) return $string;
        
        $replace_pairs = array();        
        foreach((array)$replacements as $key => $value)
            $replace_pairs['{'.$key.'}'] = $value;
        
        foreach((array)$replacements as $key => $value)
            $replace_pairs['<!--'.$key.'-->'] = $value;
                  
        return strtr($string, $replace_pairs);
    }

    /**
     * @return string
     */
    public static function guid()
    {
        if(function_exists('com_create_guid'))
        {
            return com_create_guid();
        }
        else
        {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);  // "-"
            $uuid = chr(123)    // "{"
                . substr($charid, 0, 8) 
                . $hyphen 
                . substr($charid, 8, 4) 
                . $hyphen 
                . substr($charid, 12, 4) 
                . $hyphen 
                . substr($charid, 16, 4) 
                . $hyphen 
                . substr($charid, 20, 12) 
                . chr(125);     // "}"

            return $uuid;
        }
    }
}


$test = new WinRmPhp();
$test->run('dir', '*');

