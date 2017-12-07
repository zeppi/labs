#!/bin/bash
## Usage: programs [options] ARG
##
## Options:
##   -h, --help    Display this message.
##   -v, --version Display version
##

VERSION=0.1.0

usage() {
  [ "$*" ] && echo "$0: $*"
  sed -n '/^##/,/^$/s/^## \{0,1\}//p' "$0"
  exit 2
} 2>/dev/null

#region Seting params _________________________________________________________
PROGNAME=${0##*/}

SHORTOPTS="vh" 

LONGOPTS="help,version" 

ARGS=$(getopt -s bash -o $SHORTOPTS -l $LONGOPTS -n $PROGNAME -- "$@" )

if [ $? != 0 ] ; then usage >&2 ; exit 1 ; fi

eval set -- "$ARGS"

while true;
  do
    case "$1" in
      -v|--version)
        echo "Version $VERSION"
        exit 0
        ;;
      -h|--help)
        usage "help"
        ;;
      -?)
        usage "unknown option"
        ;;
      *)
        echo $@
        break
        ;; 
    esac
  done

# args for perl script
current_path=$(dirname $0)

#endregion ____________________________________________________________________

#region Main __________________________________________________________________

echo $script_args

#endregion ____________________________________________________________________
