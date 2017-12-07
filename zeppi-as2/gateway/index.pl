#!/usr/bin/perl

##
# Gateway AS2 requests
# Customers connect to the apache server and the request is passed to the script
#
use lib;
use Cwd qw(abs_path);
use File::Basename qw(dirname);

BEGIN
{
    my $dirname = dirname(__FILE__);
    my $my_path = abs_path("$dirname/../lib");

    lib->import($my_path);
}

use strict;
use warnings qw(all);

use File::Slurp;
use Net::AS2;
use CGI;

##-Init________________________________________________________________________
my $q = new CGI;

my %configs = ();
  
my $dirname = dirname(__FILE__);


##-Process GET request_________________________________________________________
if($ENV{REQUEST_METHOD} eq "GET")
{
    print $q->header();
    print <<_SIMPLE_;
    <!doctype html>
    <html lang="en">
    <head>
      <meta charset="utf-8">
      <title>AS2 Gateway</title>
      <meta name="description" content="AS2 Gateway">
      <meta name="author" content="">
    </head>
    <body>
        <h1>AS2 Gateway</h1>
    </body>
    </html>
_SIMPLE_
        exit(0);
}

##-Process POST request________________________________________________________
if($ENV{REQUEST_METHOD} eq "POST")
{       
    my $MyId = "Mr 2";
    my $MyKey = read_file($root . "...//server.key");
    my $MyCertificate = read_file($root . "...//server.cert");
    my $PartnerId = "Mr 1";
    my $PartnerCertificate = read_file($root . "...//client.cert");
    my $PartnerUrl = "http://as2.dev";
    my $MdnAsyncUrl = "http://as2.dev";

    try
    {
        my $server = Net::AS2->new(
             MyId => $MyId, MyKey => $MyKey, MyCertificate => $MyCertificate, 
             PartnerId => $PartnerId, PartnerCertificate => $PartnerCertificate,
             PartnerUrl => $PartnerUrl,
             Mdn => 'async',
             MdnAsyncUrl => $MdnAsyncUrl
        );
    
        my $message = $server->decode_message(\%ENV, $q->param('POSTDATA'));

        if ($message->is_success)
        {
            write_file($root . "/clients/dev/Inbox/messag.txt", $message->content);        
        }
        
        my $formatter = DateTime::Format::Strptime->new(pattern => '%Y-%m-%d at %H:%M:%S %z');
        my $date = DateTime->now(formatter => $formatter);    
        my $out_message = "MDN for -
  Message ID: $ENV{HTTP_MESSAGE_ID}
  From: Mr 2
  To: Mr 1
  Received on: $date
  Status: processed
";          
       
        if ($message->is_mdn_async)
        {

            my $state = $message->serialized_state;
            my $message = Net::AS2::Message->create_from_serialized_state($state);

            $server->send_async_mdn(
                    $message->is_success ?
                        Net::AS2::MDN->create_success($message) :
                        Net::AS2::MDN->create_from_unsuccessful_message($message),
                    $ENV{HTTP_MESSAGE_ID});
        } 
        else 
        {     
            my ($new_headers, $mdn_body) = $server->prepare_sync_mdn(
                    $message->is_success ?
                        Net::AS2::MDN->create_success($message, $out_message) :
                        Net::AS2::MDN->create_from_unsuccessful_message($message, $out_message),
                    $ENV{HTTP_MESSAGE_ID});
            

            my $mh = '';
            for (my $i = 0; $i < scalar @{$new_headers}; $i += 2)
            {
                $mh .= $new_headers->[$i] . ': ' . $new_headers->[$i+1] . "\x0d\x0a";
            }
                        
            binmode(STDOUT);
            print $mh . "\x0d\x0a" . $mdn_body;    
        }
    }
    catch
    {
        print $q->header();
        print <<_SIMPLE_;
        <!doctype html>
        <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>AS2 Gateway</title>
          <meta name="description" content="AS2 Gateway">
          <meta name="author" content="">
        </head>
        <body>
            <h1>Error AS2 Gateway</h1>
        </body>
        </html>
_SIMPLE_
            exit(0);
    }       
}
