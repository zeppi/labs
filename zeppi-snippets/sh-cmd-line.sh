#!/bin/bash
## Usage: sh-cmd-line.sh [options] ARG1
##
## bash simple
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

while getopts ":i:vh" optname
  do
    case "$optname" in
      "v")
        echo "Version $VERSION"
        exit 0;
        ;;
      "i")
        echo "-i argument: $OPTARG"
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

# is for ...
param1=$1

# is for ...
param2=$2

#endregion ____________________________________________________________________

#region Main __________________________________________________________________

echo $param1
echo $param2

#endregion ____________________________________________________________________
