#!/usr/bin/perl
#region Load libs _____________________________________________________________

use lib;
use Cwd qw(abs_path);
use File::Basename qw(dirname);
BEGIN {
    my $dirname = dirname(__FILE__);
    my $my_path = abs_path("$dirname/");

    lib->import($my_path);
}

use Getopt::Long;
use Pod::Usage;

use strict;
use vars qw($VERSION);

$VERSION = '1.00';

#endregion ____________________________________________________________________
#region Seting params _________________________________________________________

# Option default values
my %opt = (

);

# Required params
my @required = qw(...);

# Catch params
GetOptions(
   \%opt,
   'help|?'
) or die pod2usage(2); # dies if an unlisted option is given

# if an option isn't specified, it isn't set in the hash so
# we look for the ones we want and make certain they're there
die pod2usage(1) if grep(!exists $opt{$_},@required);

#endregion ____________________________________________________________________

&main();

#region Main __________________________________________________________________
sub main
{

}

exit($?>>8);
#endregion ____________________________________________________________________

__END__

=head1 NAME

Programme name

=head1 SYNOPSIS

Programme [options]

=head1 OPTIONS

=over 1

=item B<--help>

Full documentation

=back

=head1 DESCRIPTION

B<This program> will ....

=cut
