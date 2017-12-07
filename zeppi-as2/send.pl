#!/usr/bin/perl
#region Load libs _____________________________________________________________

use lib;
use Cwd qw(abs_path);
use File::Basename qw(dirname);
BEGIN {
    my $dirname = dirname(__FILE__);
    my $my_path = abs_path("$dirname/lib");

    lib->import($my_path);
}

use Getopt::Long;
use Pod::Usage;

use strict;
use vars qw($VERSION);

$VERSION = '1.00';

use File::Slurp;
use Net::AS2;
use File::Copy qw(move);

use Data::Dumper;

#endregion ____________________________________________________________________
#region Seting params _________________________________________________________

# Required params
my @required = qw();

# Catch params
GetOptions(
   \%opt,
   'help|?'
) or die pod2usage(2); # dies if an unlisted option is given

# if an option isn't specified, it isn't set in the hash so
# we look for the ones we want and make certain they're there
die pod2usage(1) if grep(!exists $opt{$_},@required);
pod2usage( -verbose => 1 ) if $opt{'help'};

#endregion ____________________________________________________________________

&main();

#region Main __________________________________________________________________
sub main
{
    ##--Init-------------------------------------------------------------------
    $ENV{PERL_NET_HTTPS_SSL_SOCKET_CLASS} = "Net::SSL";
    
    my $dirname = dirname(__FILE__);   
    my $root = abs_path($dirname);
            
    ##--Process----------------------------------------------------------------
    ##
    # Load key and certificate
    #
    my $MyKey = read_file($root . "...//client.key");
    my $MyCertificate = read_file($root . "...//client.cert");
    my $PartnerCertificate = read_file($root . "...//server.cert");

    ##
    # Load fils in client sent folder
    #
    my @files = read_dir($root . "/clients/dev/Send");
    
    ##
    # Process files
    #
    foreach my $file (@files)
    {           
        if($file ne "empty")
        {
            ##
            # Initialise as2
            #               
            my $as2 = Net::AS2->new(
                MyId => "Mr 1",
                MyKey => $MyKey,
                PartnerId => "http://as2.dev",
                PartnerUrl => "http://as2.dev",
                MyCertificate => $MyCertificate,            
                PartnerCertificate => $PartnerCertificate);
            
            ##
            # Read data file and send
            #
            my $data = read_file($root . "/clients/dev/Send/".$file);
            
            #-- Force out ip @LWP::Protocol::http::EXTRA_SOCK_OPTS = {LocalAddr => "127.0.0.2"};
            
            #-- Send                
            my ($mdn_temp, $mic1) = $as2->send(
                $data, 'Type' => 'application/xml', 'MessageId' => $file ."\@localhost");
            
            if($mdn_temp->{'success'} == 1)
            {            
                print "Message " . $file . " was sent \n";
            }
            else
            {               
                print "Message " . $error_file . "\n";                    
            }
        }
    }
    
    $dbh->disconnect;
}

exit($?>>8);
#endregion ____________________________________________________________________

__END__

=head1 NAME

send.pl

=head1 SYNOPSIS

send.pl [options]

=head1 OPTIONS

=over 1

=item B<--help>

Show help

=back

=head1 DESCRIPTION

It will look for messages in the folder Send client and send it 

=cut
