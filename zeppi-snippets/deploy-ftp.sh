#!/bin/bash
## Usage: deploy-ftp.sh [options]
##
## Deploy using ftp and rsync 
##
## Options:
##   -h, --help    Display this message.
##   -v            Display version
##

VERSION=0.1.0

usage() {
  [ "$*" ] && echo "$0: $*"
  sed -n '/^##/,/^$/s/^## \{0,1\}//p' "$0"
  exit 2
} 2>/dev/null

#region Seting params _________________________________________________________
if [ $# == 0 ] ; then
    usage "unknown option"
fi

while getopts "vh" optname
  do
    case "$optname" in
      "v")
        echo "Version $VERSION"
        exit 0;
        ;;
      "h")
        usage "help"
        exit 0;
        ;;     
      "?")
        usage "unknown option"
        ;;
      ":")
        echo "No argument value for option $OPTARG"
        exit 0;
        ;;
      *)
        echo "Unknown error while processing options"
        exit 0;
        ;;
    esac
  done

shift $(($OPTIND - 1))

#endregion ____________________________________________________________________

#region Main __________________________________________________________________

RSYNC_OPTIONS="--stats \
 --archive \
 --hard-links \
 --numeric-ids \
 --delete \
 --force \
 --delete-during -v "

EXCLUDE="--exclude=cache/* \
 --exclude=log/* \
 --exclude=.svn \
 --exclude=.git " 
 
sudo curlftpfs -o allow_other ftp-user:ftp-password@site.ch/ /mnt/ftpsys

SRC=/home/....
DST=/mnt/ftpsys

rsync $RSYNC_OPTIONS $EXCLUDE $SRC $DST

sudo fusermount -u /mnt/ftpsys

#endregion ____________________________________________________________________
