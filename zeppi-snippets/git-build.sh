#!/bin/bash
## Usage: git-build.sh [options] src_path dst_path
##
## Svn export equivalent
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

if [ -z "$1" -o -z "$2" ] ; then
    usage "unknown option"
fi

src=$1
dst=$2
#endregion ____________________________________________________________________

#region Main __________________________________________________________________

cd $src
git archive HEAD | (cd $dst && tar -xvf -)

#endregion ____________________________________________________________________
