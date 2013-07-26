#!/bin/bash
## Usage: deploy.sh [options]
##
## Basic deploy using rsync 
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
 
SRC=/home/....
SERVERS="..."
DST=/home/web...

rsync $RSYNC_OPTIONS $EXCLUDE $SRC ssh-user@$SERVERS:$DST

#endregion ____________________________________________________________________
